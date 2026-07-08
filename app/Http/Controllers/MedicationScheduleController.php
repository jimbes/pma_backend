<?php

namespace App\Http\Controllers;

use App\Models\MedicationSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MedicationScheduleController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request, $medicationId = null)
    {
        $query = MedicationSchedule::where('couple_id', auth()->user()->couple_id);

        if ($medicationId) {
            $query->where('medication_id', $medicationId);
        }

        $schedules = $query->with('medication')->get();
        return response()->json(['schedules' => $schedules]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'medication_id' => 'required|exists:medications,id',
            // Optional: medications don't need to be pre-assigned to a
            // journey phase - in practice the doctor decides dosage/duration
            // per visit, before the couple necessarily knows which phase
            // that falls under. The schedule's own start/end date is what
            // actually governs agenda visibility; the phase link (if any)
            // is just an informational tag.
            'journey_stage_id' => 'nullable|exists:journey_stages,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'frequency' => 'required|in:daily,specific_days',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|min:0|max:6',
            'reminder_times' => 'required|array',
            'reminder_times.*' => 'string',
            // Despite the column name, this is minutes-before-dose (max 24h worth).
            'reminder_offset_hours' => 'integer|min:0|max:1440',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ]);

        $schedule = MedicationSchedule::create([
            'couple_id' => auth()->user()->couple_id,
            'medication_id' => $request->medication_id,
            'journey_stage_id' => $request->journey_stage_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'frequency' => $request->frequency,
            'days_of_week' => $request->frequency === 'specific_days' ? $request->days_of_week : null,
            'reminder_times' => $request->reminder_times,
            'reminder_offset_hours' => $request->reminder_offset_hours ?? 1,
            'notify_user_1' => $request->boolean('notify_user_1', true),
            'notify_user_2' => $request->boolean('notify_user_2', true),
        ]);

        return response()->json(['schedule' => $schedule], 201);
    }

    public function show($id)
    {
        $schedule = MedicationSchedule::findOrFail($id);
        $this->authorize('view', $schedule);
        return response()->json(['schedule' => $schedule->load('medication')]);
    }

    public function update(Request $request, $id)
    {
        $schedule = MedicationSchedule::findOrFail($id);
        $this->authorize('update', $schedule);

        $request->validate([
            'journey_stage_id' => 'nullable|exists:journey_stages,id',
            'start_date' => 'date',
            'end_date' => 'nullable|date',
            'frequency' => 'in:daily,specific_days',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|min:0|max:6',
            'reminder_times' => 'array',
            'reminder_times.*' => 'string',
            // Despite the column name, this is minutes-before-dose (max 24h worth).
            'reminder_offset_hours' => 'integer|min:0|max:1440',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ]);

        $schedule->update($request->only([
            'journey_stage_id',
            'start_date',
            'end_date',
            'frequency',
            'days_of_week',
            'reminder_times',
            'reminder_offset_hours',
            'notify_user_1',
            'notify_user_2',
        ]));
        return response()->json(['schedule' => $schedule]);
    }

    public function destroy($id)
    {
        $schedule = MedicationSchedule::findOrFail($id);
        $this->authorize('delete', $schedule);
        $schedule->delete();
        return response()->json(['message' => 'Schedule deleted']);
    }
}
