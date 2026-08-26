<?php

namespace App\Http\Controllers;

use App\Http\Concerns\ChecksOptimisticConcurrency;
use App\Models\JourneyStage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class JourneyStageController extends Controller
{
    use AuthorizesRequests, ChecksOptimisticConcurrency;

    public function index()
    {
        $cycle = auth()->user()->couple->currentTreatmentCycle();
        $stages = $cycle->journeyStages()->orderBy('order')->orderBy('start_date')->get();
        return response()->json(['journey_stages' => $stages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:preparation,stimulation,controle,declenchement,ponction,transfert,attente_test',
            'custom_name' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'start_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_days' => 'nullable|integer|min:1',
            'manual_end_date' => 'boolean',
            'manual_start_date' => 'boolean',
            'status' => 'in:upcoming,in_progress,done,skipped',
            'reminder_enabled' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        // A new stage always belongs to the couple's current cycle - never a
        // client-supplied value - so it can't accidentally land in an
        // archived (read-only) cycle.
        $stage = JourneyStage::create([
            'couple_id' => auth()->user()->couple_id,
            'treatment_cycle_id' => auth()->user()->couple->currentTreatmentCycle()->id,
            'type' => $request->type,
            'custom_name' => $request->custom_name,
            'order' => $request->order ?? 0,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'duration_days' => $request->duration_days,
            'manual_end_date' => $request->boolean('manual_end_date', false),
            'manual_start_date' => $request->boolean('manual_start_date', false),
            'status' => $request->status ?? 'upcoming',
            'reminder_enabled' => $request->boolean('reminder_enabled', true),
            'notes' => $request->notes,
        ]);

        return response()->json(['journey_stage' => $stage], 201);
    }

    public function show($id)
    {
        $stage = JourneyStage::findOrFail($id);
        $this->authorize('view', $stage);
        return response()->json(['journey_stage' => $stage]);
    }

    public function update(Request $request, $id)
    {
        $stage = JourneyStage::findOrFail($id);
        $this->authorize('update', $stage);

        $request->validate([
            'type' => 'in:preparation,stimulation,controle,declenchement,ponction,transfert,attente_test',
            'custom_name' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'start_date' => 'date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_days' => 'nullable|integer|min:1',
            'manual_end_date' => 'boolean',
            'manual_start_date' => 'boolean',
            'status' => 'in:upcoming,in_progress,done,skipped',
            'reminder_enabled' => 'boolean',
            'notes' => 'nullable|string',
            'client_known_updated_at' => 'nullable|date',
        ]);

        $this->assertNotStale($stage, $request->client_known_updated_at);

        // Skipping a stage means it's excluded from the date chain going
        // forward and its medication reminders should stop - cap (don't
        // delete, history stays intact) any linked schedule's end_date at
        // yesterday so SendNotifications' date-range filter naturally
        // excludes it, mirroring the same range check it already uses.
        if ($request->status === 'skipped' && $stage->status !== 'skipped') {
            $yesterday = now()->subDay()->toDateString();
            foreach ($stage->medicationSchedules as $schedule) {
                if (!$schedule->end_date || $schedule->end_date->toDateString() >= $yesterday) {
                    $schedule->update(['end_date' => $yesterday]);
                }
            }
        }

        $stage->update($request->only([
            'type',
            'custom_name',
            'order',
            'start_date',
            'start_time',
            'end_date',
            'duration_days',
            'manual_end_date',
            'manual_start_date',
            'status',
            'reminder_enabled',
            'notes',
        ]));
        return response()->json(['journey_stage' => $stage]);
    }

    public function close(Request $request, $id)
    {
        $stage = JourneyStage::findOrFail($id);
        $this->authorize('update', $stage);
        $this->assertNotStale($stage, $request->client_known_updated_at);

        $stage->update([
            'end_date' => now()->toDateString(),
            'status' => 'done',
        ]);

        return response()->json(['journey_stage' => $stage]);
    }

    public function destroy(Request $request, $id)
    {
        $stage = JourneyStage::findOrFail($id);
        $this->authorize('delete', $stage);
        $this->assertNotStale($stage, $request->client_known_updated_at);
        $stage->delete();
        return response()->json(['message' => 'Journey stage deleted']);
    }
}
