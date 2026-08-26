<?php

namespace App\Http\Controllers;

use App\Http\Concerns\ChecksOptimisticConcurrency;
use App\Models\NotificationPreference;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class NotificationPreferenceController extends Controller
{
    use AuthorizesRequests, ChecksOptimisticConcurrency;

    public function index()
    {
        $preferences = NotificationPreference::where('user_id', auth()->id())->get();
        return response()->json(['notification_preferences' => $preferences]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:medication_reminder,appointment_reminder,journey_stage_reminder',
            'channel' => 'required|in:push,email,both',
            'enabled' => 'boolean',
            'reminder_minutes_before' => 'integer|min:0',
        ]);

        $preference = NotificationPreference::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'type' => $request->type,
            ],
            [
                'channel' => $request->channel,
                'enabled' => $request->boolean('enabled', true),
                'reminder_minutes_before' => $request->reminder_minutes_before ?? 15,
            ]
        );

        return response()->json(['notification_preference' => $preference], 201);
    }

    public function show($id)
    {
        $preference = NotificationPreference::findOrFail($id);
        $this->authorize('view', $preference);
        return response()->json(['notification_preference' => $preference]);
    }

    public function update(Request $request, $id)
    {
        $preference = NotificationPreference::findOrFail($id);
        $this->authorize('update', $preference);

        $request->validate([
            'type' => 'in:medication_reminder,appointment_reminder,journey_stage_reminder',
            'channel' => 'in:push,email,both',
            'enabled' => 'boolean',
            'reminder_minutes_before' => 'integer|min:0',
            'client_known_updated_at' => 'nullable|date',
        ]);

        $this->assertNotStale($preference, $request->client_known_updated_at);

        $preference->update($request->except('client_known_updated_at'));
        return response()->json(['notification_preference' => $preference]);
    }

    public function destroy(Request $request, $id)
    {
        $preference = NotificationPreference::findOrFail($id);
        $this->authorize('delete', $preference);
        $this->assertNotStale($preference, $request->client_known_updated_at);
        $preference->delete();
        return response()->json(['message' => 'Notification preference deleted']);
    }
}
