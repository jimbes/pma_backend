<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load('couple');
        return response()->json(['user' => new UserResource($user)]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:users,email,' . auth()->id(),
            'language' => 'string|in:fr,en,de,es',
        ]);

        $user = auth()->user();
        $user->update($validated);
        $user->load('couple');

        return response()->json(['user' => new UserResource($user)]);
    }
}
