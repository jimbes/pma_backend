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
        $stages = auth()->user()->couple->journeyStages()->orderBy('start_date')->get();
        return response()->json(['journey_stages' => $stages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'treatment_cycle_id' => 'nullable|integer|exists:treatment_cycles,id',
            'type' => 'required|in:stimulation,declenchement,ponction,transfert,attente_test',
            'start_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'status' => 'in:upcoming,in_progress,done',
            'reminder_enabled' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $stage = JourneyStage::create([
            'couple_id' => auth()->user()->couple_id,
            'treatment_cycle_id' => $request->treatment_cycle_id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
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
            'start_date' => 'date',
            'start_time' => 'nullable|date_format:H:i',
            'status' => 'in:upcoming,in_progress,done',
            'reminder_enabled' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $stage->update($request->all());
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
