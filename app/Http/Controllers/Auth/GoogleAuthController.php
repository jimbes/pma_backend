<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Couple;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{
    public function login()
    {
        request()->validate(['id_token' => 'required|string']);

        $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => request('id_token'),
        ]);

        if (!$response->successful()) {
            return response()->json(['message' => 'Jeton Google invalide'], 401);
        }

        $payload = $response->json();

        if (($payload['aud'] ?? null) !== config('services.google.client_id')) {
            return response()->json(['message' => 'Jeton Google invalide'], 401);
        }

        if (($payload['email_verified'] ?? 'false') !== 'true') {
            return response()->json(['message' => 'Email Google non vérifié'], 401);
        }

        $googleId = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'] ?? explode('@', $email)[0];

        $user = User::where('google_id', $googleId)->first();

        if (!$user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                // Existing password-based account with a matching email: link it.
                $user->update(['google_id' => $googleId]);
            } else {
                $couple = Couple::create();
                $user = User::create([
                    'couple_id' => $couple->id,
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                ]);
            }
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Connecté avec Google',
            'user' => new UserResource($user->load('couple')),
            'token' => $token,
        ]);
    }
}
