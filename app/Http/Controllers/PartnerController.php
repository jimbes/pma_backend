<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function show()
    {
        $couple = auth()->user()->couple;
        $partner = $couple->users()->where('id', '!=', auth()->id())->first();

        if (!$partner) {
            return response()->json(['partner' => null]);
        }

        return response()->json(['partner' => new UserResource($partner)]);
    }

    public function remove($id)
    {
        $user = auth()->user();
        $partner = $user->couple->users()->where('id', $id)->firstOrFail();

        $partner->delete();

        return response()->json(['message' => 'Partner removed from couple']);
    }
}
