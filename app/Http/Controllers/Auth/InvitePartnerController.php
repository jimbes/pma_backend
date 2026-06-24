<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CoupleInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitePartnerController extends Controller
{
    public function invite()
    {
        request()->validate(['email' => 'required|email|unique:users']);

        $user = auth()->user();
        $token = Str::random(64);

        $invitation = CoupleInvitation::create([
            'couple_id' => $user->couple_id,
            'inviter_id' => $user->id,
            'invitee_email' => request('email'),
            'token' => $token,
            'expires_at' => now()->addDays(7),
        ]);

        // TODO: Send email (requires mail configuration)

        return response()->json([
            'message' => 'Invitation created (email sending not configured)',
            'invitation' => [
                'id' => $invitation->id,
                'invitee_email' => $invitation->invitee_email,
                'expires_at' => $invitation->expires_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
