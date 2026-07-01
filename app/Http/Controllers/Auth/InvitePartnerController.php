<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CoupleInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class InvitePartnerController extends Controller
{
    public function invite()
    {
        request()->validate(['email' => 'required|email']);

        $user = auth()->user();
        $email = request('email');

        if (strcasecmp($email, $user->email) === 0) {
            return response()->json(['message' => "Vous ne pouvez pas vous inviter vous-même"], 422);
        }

        $myPartnerCount = $user->couple
            ? $user->couple->users()->where('id', '!=', $user->id)->count()
            : 0;

        if ($myPartnerCount > 0) {
            return response()->json(['message' => 'Vous avez déjà un·e partenaire'], 422);
        }

        $invitedUser = User::where('email', $email)->first();

        if ($invitedUser) {
            $invitedUserPartnerCount = $invitedUser->couple
                ? $invitedUser->couple->users()->where('id', '!=', $invitedUser->id)->count()
                : 0;

            if ($invitedUserPartnerCount > 0) {
                return response()->json([
                    'message' => 'Cette personne est déjà en couple avec quelqu\'un d\'autre',
                ], 422);
            }
        }

        $token = Str::random(64);

        $invitation = CoupleInvitation::create([
            'couple_id' => $user->couple_id,
            'inviter_id' => $user->id,
            'invitee_email' => $email,
            'token' => $token,
            'expires_at' => now()->addDays(7),
        ]);

        return response()->json([
            'message' => 'Invitation créée',
            'invitation' => [
                'id' => $invitation->id,
                'invitee_email' => $invitation->invitee_email,
                'token' => $invitation->token,
                'expires_at' => $invitation->expires_at->format('Y-m-d H:i:s'),
                'existing_user' => $invitedUser !== null,
            ],
        ], 201);
    }
}
