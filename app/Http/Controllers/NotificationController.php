<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())
            ->where('couple_id', auth()->user()->couple_id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json($notifications);
    }

    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        $this->authorize('view', $notification);
        return response()->json(['notification' => $notification]);
    }
}
