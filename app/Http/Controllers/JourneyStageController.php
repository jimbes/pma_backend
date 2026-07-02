<?php

namespace App\Http\Controllers;

use App\Models\JourneyStage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class JourneyStageController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $stages = auth()->user()->couple->journeyStages()->orderBy('order')->orderBy('start_date')->get();
        return response()->json(['journey_stages' => $stages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'treatment_cycle_id' => 'nullable|integer|exists:treatment_cycles,id',
            'type' => 'required|in:stimulation,declenchement,ponction,transfert,attente_test',
            'custom_name' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'start_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_days' => 'nullable|integer|min:1',
            'manual_end_date' => 'boolean',
            'status' => 'in:upcoming,in_progress,done',
            'reminder_enabled' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $stage = JourneyStage::create([
            'couple_id' => auth()->user()->couple_id,
            'treatment_cycle_id' => $request->treatment_cycle_id,
            'type' => $request->type,
            'custom_name' => $request->custom_name,
            'order' => $request->order ?? 0,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'duration_days' => $request->duration_days,
            'manual_end_date' => $request->boolean('manual_end_date', false),
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
            'treatment_cycle_id' => 'nullable|integer|exists:treatment_cycles,id',
            'type' => 'in:stimulation,declenchement,ponction,transfert,attente_test',
            'custom_name' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'start_date' => 'date',
            'start_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_days' => 'nullable|integer|min:1',
            'manual_end_date' => 'boolean',
            'status' => 'in:upcoming,in_progress,done',
            'reminder_enabled' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $stage->update($request->only([
            'treatment_cycle_id',
            'type',
            'custom_name',
            'order',
            'start_date',
            'start_time',
            'end_date',
            'duration_days',
            'manual_end_date',
            'status',
            'reminder_enabled',
            'notes',
        ]));
        return response()->json(['journey_stage' => $stage]);
    }

    public function close($id)
    {
        $stage = JourneyStage::findOrFail($id);
        $this->authorize('update', $stage);

        $stage->update([
            'end_date' => now()->toDateString(),
            'status' => 'done',
        ]);

        return response()->json(['journey_stage' => $stage]);
    }

    public function destroy($id)
    {
        $stage = JourneyStage::findOrFail($id);
        $this->authorize('delete', $stage);
        $stage->delete();
        return response()->json(['message' => 'Journey stage deleted']);
    }
}
