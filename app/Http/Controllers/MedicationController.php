<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $medications = auth()->user()->couple->medications()
            ->where('active', true)
            ->with('schedules')
            ->get();
        return response()->json(['medications' => $medications]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'dosage' => 'required|string',
            'unit' => 'required|string',
            'form' => 'nullable|in:injection,comprimé,patch,ovule',
            'for_partner' => 'in:user1,user2,both',
            'description' => 'nullable|string',
        ]);

        $medication = Medication::create([
            'couple_id' => auth()->user()->couple_id,
            'name' => $request->name,
            'dosage' => $request->dosage,
            'unit' => $request->unit,
            'form' => $request->form,
            'for_partner' => $request->for_partner ?? 'both',
            'description' => $request->description,
            'active' => true,
        ]);

        return response()->json(['medication' => $medication], 201);
    }

    public function show($id)
    {
        $medication = Medication::findOrFail($id);
        $this->authorize('view', $medication);
        return response()->json(['medication' => $medication->load('schedules')]);
    }

    public function update(Request $request, $id)
    {
        $medication = Medication::findOrFail($id);
        $this->authorize('update', $medication);

        $request->validate([
            'name' => 'string|max:255',
            'dosage' => 'string',
            'unit' => 'string',
            'form' => 'nullable|in:injection,comprimé,patch,ovule',
            'for_partner' => 'in:user1,user2,both',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $medication->update($request->all());
        return response()->json(['medication' => $medication]);
    }

    public function destroy($id)
    {
        $medication = Medication::findOrFail($id);
        $this->authorize('delete', $medication);
        $medication->update(['active' => false]);
        return response()->json(['message' => 'Medication deactivated']);
    }
}
