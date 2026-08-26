<?php

namespace App\Http\Controllers;

use App\Models\TreatmentCycle;

class TreatmentCycleController extends Controller
{
    public function index()
    {
        $cycles = auth()->user()->couple->treatmentCycles()->orderByDesc('cycle_number')->get();
        return response()->json(['treatment_cycles' => $cycles]);
    }

    public function current()
    {
        $cycle = auth()->user()->couple->currentTreatmentCycle();
        return response()->json(['treatment_cycle' => $cycle]);
    }

    public function startNew()
    {
        $couple = auth()->user()->couple;
        $current = $couple->currentTreatmentCycle();

        $cycle = $couple->treatmentCycles()->create([
            'cycle_number' => $current->cycle_number + 1,
            'start_date' => now()->toDateString(),
        ]);

        return response()->json(['treatment_cycle' => $cycle], 201);
    }

    public function stages($id)
    {
        $cycle = TreatmentCycle::findOrFail($id);
        abort_unless($cycle->couple_id === auth()->user()->couple_id, 403);

        $stages = $cycle->journeyStages()->orderBy('order')->orderBy('start_date')->get();
        return response()->json(['journey_stages' => $stages]);
    }
}
