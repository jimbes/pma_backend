<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sends push notifications via Firebase Cloud Messaging's HTTP v1 API.
 * v1 requires an OAuth2 access token (not the old static "server key"),
 * obtained here via a self-signed JWT bearer assertion against the service
 * account - firebase/php-jwt (already a dependency for Google Sign-In) does
 * the signing, so no extra Google auth library is needed.
 */
class FirebaseMessagingService
{
    private const SCOPE = 'https://www.googleapis.com/auth/firebase.messaging';
    private const CACHE_KEY = 'firebase_fcm_access_token';

    private ?array $credentials = null;

    private function credentials(): array
    {
        if ($this->credentials !== null) {
            return $this->credentials;
        }

        $path = config('services.firebase.credentials_path');
        $fullPath = str_starts_with($path, '/') ? $path : base_path($path);

        if (!file_exists($fullPath)) {
            throw new \RuntimeException("Firebase credentials file not found at {$fullPath}");
        }

        $this->credentials = json_decode(file_get_contents($fullPath), true);
        return $this->credentials;
    }

    private function accessToken(): string
    {
        return Cache::remember(self::CACHE_KEY, 3000, function () {
            $creds = $this->credentials();
            $now = time();

            $jwt = JWT::encode([
                'iss' => $creds['client_email'],
                'scope' => self::SCOPE,
                'aud' => $creds['token_uri'],
                'iat' => $now,
                'exp' => $now + 3600,
            ], $creds['private_key'], 'RS256');

            $response = Http::asForm()->post($creds['token_uri'], [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if (!$response->successful()) {
                throw new \RuntimeException(
                    'Failed to obtain Firebase access token: ' . $response->body()
                );
            }

            return $response->json('access_token');
        });
    }

    /**
     * Sends to a single device token. Returns ['success' => bool,
     * 'invalid_token' => bool] - invalid_token flags tokens the caller
     * should deactivate (uninstalled app, expired token, etc.) rather than
     * retry.
     *
     * Data-only (no top-level "notification" block) so Android never
     * auto-displays this - the client always decides how/whether to show
     * it itself (see firebaseMessagingBackgroundHandler /
     * PushNotificationService.onMessage in the Flutter app), which is what
     * lets it compute a notification id matching its own local-alarm
     * scheduler and avoid showing a duplicate when both fire for the same
     * reminder. $data must include 'title'/'body' since there's no
     * notification block to carry them.
     */
    public function sendToToken(string $token, array $data): array
    {
        $projectId = config('services.firebase.project_id');

        try {
            $response = Http::withToken($this->accessToken())
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'token' => $token,
                        'data' => array_map('strval', $data),
                        'android' => [
                            'priority' => 'high',
                        ],
                    ],
                ]);

            if ($response->successful()) {
                return ['success' => true, 'invalid_token' => false];
            }

            $errorStatus = $response->json('error.status');
            $invalidToken = in_array($errorStatus, ['UNREGISTERED', 'NOT_FOUND', 'INVALID_ARGUMENT'], true);

            Log::warning('FCM send failed', [
                'status' => $response->status(),
                'error' => $response->json('error'),
            ]);

            return ['success' => false, 'invalid_token' => $invalidToken];
        } catch (\Throwable $e) {
            Log::error('FCM send exception: ' . $e->getMessage());
            return ['success' => false, 'invalid_token' => false];
        }
    }
}
