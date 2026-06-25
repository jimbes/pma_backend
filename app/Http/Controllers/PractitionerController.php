<?php

namespace App\Http\Controllers;

use App\Models\Practitioner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PractitionerController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $practitioners = auth()->user()->couple->practitioners()->get();
        return response()->json(['practitioners' => $practitioners]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'clinic_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $practitioner = Practitioner::create([
            'couple_id' => auth()->user()->couple_id,
            'name' => $request->name,
            'specialty' => $request->specialty,
            'phone' => $request->phone,
            'email' => $request->email,
            'clinic_name' => $request->clinic_name,
            'address' => $request->address,
        ]);

        return response()->json(['practitioner' => $practitioner], 201);
    }

    public function show($id)
    {
        $practitioner = Practitioner::findOrFail($id);
        $this->authorize('view', $practitioner);
        return response()->json(['practitioner' => $practitioner]);
    }

    public function update(Request $request, $id)
    {
        $practitioner = Practitioner::findOrFail($id);
        $this->authorize('update', $practitioner);

        $request->validate([
            'name' => 'string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'clinic_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $practitioner->update($request->all());
        return response()->json(['practitioner' => $practitioner]);
    }

    public function destroy($id)
    {
        $practitioner = Practitioner::findOrFail($id);
        $this->authorize('delete', $practitioner);
        $practitioner->delete();
        return response()->json(['message' => 'Practitioner deleted']);
    }
}
