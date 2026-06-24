<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    use AuthorizesRequests;
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'platform' => 'required|in:flutter,pwa',
        ]);

        $deviceToken = DeviceToken::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'token' => $request->token,
            ],
            [
                'platform' => $request->platform,
                'active' => true,
                'last_used_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Device token registered',
            'device_token' => $deviceToken,
        ], 201);
    }

    public function revoke($id)
    {
        $token = DeviceToken::findOrFail($id);
        $this->authorize('delete', $token);
        $token->update(['active' => false]);
        return response()->json(['message' => 'Device token revoked']);
    }
}
