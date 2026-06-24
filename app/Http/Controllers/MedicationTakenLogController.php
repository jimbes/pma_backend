<?php

namespace App\Http\Controllers;

use App\Models\MedicationTakenLog;
use App\Models\MedicationSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MedicationTakenLogController extends Controller
{
    use AuthorizesRequests;
    public function history($scheduleId)
    {
        $schedule = MedicationSchedule::findOrFail($scheduleId);
        $this->authorize('view', $schedule);

        $logs = $schedule->takenLog()
            ->orderBy('date', 'desc')
            ->get();

        return response()->json(['logs' => $logs]);
    }

    public function markTaken(Request $request, $scheduleId)
    {
        $schedule = MedicationSchedule::findOrFail($scheduleId);
        $this->authorize('update', $schedule);

        $request->validate(['date' => 'required|date']);

        $log = MedicationTakenLog::updateOrCreate(
            [
                'medication_schedule_id' => $scheduleId,
                'date' => $request->date,
            ],
            [
                'taken' => true,
                'taken_at' => now()->format('H:i:s'),
                'user_logged_by' => auth()->id(),
                'notes' => $request->notes,
            ]
        );

        return response()->json(['log' => $log]);
    }

    public function markNotTaken(Request $request, $scheduleId)
    {
        $schedule = MedicationSchedule::findOrFail($scheduleId);
        $this->authorize('update', $schedule);

        $request->validate(['date' => 'required|date']);

        $log = MedicationTakenLog::updateOrCreate(
            [
                'medication_schedule_id' => $scheduleId,
                'date' => $request->date,
            ],
            [
                'taken' => false,
                'taken_at' => null,
                'user_logged_by' => auth()->id(),
                'notes' => $request->notes,
            ]
        );

        return response()->json(['log' => $log]);
    }
}
