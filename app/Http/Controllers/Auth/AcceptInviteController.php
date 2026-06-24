<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\CoupleInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AcceptInviteController extends Controller
{
    public function accept($token)
    {
        $invitation = CoupleInvitation::where('token', $token)->first();

        if (!$invitation) {
            return response()->json(['message' => 'Invitation not found'], 404);
        }

        if ($invitation->isExpired()) {
            return response()->json(['message' => 'Invitation has expired'], 410);
        }

        if ($invitation->accepted) {
            return response()->json(['message' => 'Invitation already accepted'], 400);
        }

        request()->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'couple_id' => $invitation->couple_id,
            'name' => request('name'),
            'email' => $invitation->invitee_email,
            'password' => Hash::make(request('password')),
        ]);

        $invitation->update(['accepted' => true, 'accepted_at' => now()]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Invitation accepted successfully',
            'user' => new UserResource($user->load('couple')),
            'token' => $token,
        ], 201);
    }
}
