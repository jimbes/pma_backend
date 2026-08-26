<?php

namespace App\Http\Controllers;

use App\Http\Concerns\ChecksOptimisticConcurrency;
use App\Models\MedicationTakenLog;
use App\Models\MedicationSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MedicationTakenLogController extends Controller
{
    use AuthorizesRequests, ChecksOptimisticConcurrency;

    /// All taken/not-taken logs across every schedule belonging to the
    /// couple - lets the app show "mark as taken" state on Home/Agenda for
    /// both partners without one request per schedule.
    public function indexForCouple(Request $request)
    {
        $scheduleIds = MedicationSchedule::where('couple_id', auth()->user()->couple_id)
            ->pluck('id');

        $logs = MedicationTakenLog::whereIn('medication_schedule_id', $scheduleIds)->get();

        return response()->json(['logs' => $logs]);
    }

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

        $request->validate([
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'client_known_updated_at' => 'nullable|date',
        ]);

        $key = [
            'medication_schedule_id' => $scheduleId,
            'date' => $request->date,
            'time' => $request->time,
        ];
        $existing = MedicationTakenLog::where($key)->first();
        if ($existing) {
            $this->assertNotStale($existing, $request->client_known_updated_at);
        }

        $log = MedicationTakenLog::updateOrCreate(
            $key,
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

        $request->validate([
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
            'client_known_updated_at' => 'nullable|date',
        ]);

        $key = [
            'medication_schedule_id' => $scheduleId,
            'date' => $request->date,
            'time' => $request->time,
        ];
        $existing = MedicationTakenLog::where($key)->first();
        if ($existing) {
            $this->assertNotStale($existing, $request->client_known_updated_at);
        }

        $log = MedicationTakenLog::updateOrCreate(
            $key,
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
