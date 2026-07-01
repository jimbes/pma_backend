<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\CoupleInvitation;

class JoinCoupleController extends Controller
{
    public function join()
    {
        request()->validate(['token' => 'required|string']);

        $invitation = CoupleInvitation::where('token', request('token'))->first();

        if (!$invitation) {
            return response()->json(['message' => "Code d'invitation introuvable"], 404);
        }

        if ($invitation->isExpired()) {
            return response()->json(['message' => 'Ce code d\'invitation a expiré'], 410);
        }

        if ($invitation->accepted) {
            return response()->json(['message' => 'Cette invitation a déjà été utilisée'], 400);
        }

        $user = auth()->user();

        if (strcasecmp($invitation->invitee_email, $user->email) !== 0) {
            return response()->json(['message' => "Ce code d'invitation ne vous est pas destiné"], 403);
        }

        $currentPartnerCount = $user->couple
            ? $user->couple->users()->where('id', '!=', $user->id)->count()
            : 0;

        if ($currentPartnerCount > 0) {
            return response()->json(['message' => 'Vous avez déjà un·e partenaire'], 422);
        }

        $user->update(['couple_id' => $invitation->couple_id]);
        $invitation->update(['accepted' => true, 'accepted_at' => now()]);

        return response()->json([
            'message' => 'Vous avez rejoint le couple avec succès',
            'user' => new UserResource($user->fresh()->load('couple')),
        ]);
    }
}
