<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class MeController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load('couple');
        return response()->json(['user' => new UserResource($user)]);
    }
}
