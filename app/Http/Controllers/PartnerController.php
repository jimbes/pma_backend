<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Couple;

class PartnerController extends Controller
{
    public function show()
    {
        $couple = auth()->user()->couple;
        $partner = $couple ? $couple->users()->where('id', '!=', auth()->id())->first() : null;

        if (!$partner) {
            return response()->json(['partner' => null]);
        }

        return response()->json(['partner' => new UserResource($partner)]);
    }

    public function remove($id)
    {
        $user = auth()->user();
        $partner = $user->couple->users()->where('id', $id)->firstOrFail();

        // Unlink rather than delete: the partner keeps their account and data,
        // just moves to a fresh solo couple instead of losing everything.
        $newCouple = Couple::create();
        $partner->update(['couple_id' => $newCouple->id]);

        return response()->json(['message' => 'Partenaire retiré du couple']);
    }
}
