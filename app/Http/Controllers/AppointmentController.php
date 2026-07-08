<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $appointments = auth()->user()->couple->appointments()->get();
        return response()->json(['appointments' => $appointments]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|date_format:H:i',
            'type' => 'nullable|in:echo,blood_test,consult,ponction,transfert,other',
            // Minutes before the appointment - can have several reminders
            // (e.g. 24h before AND 12h before), not just one.
            'reminder_offsets' => 'array',
            'reminder_offsets.*' => 'integer|min:0',
            'location' => 'nullable|string',
            'doctor_name' => 'nullable|string',
            'description' => 'nullable|string',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ]);

        $appointment = Appointment::create([
            'couple_id' => auth()->user()->couple_id,
            'created_by' => auth()->id(),
            'title' => $request->title,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'type' => $request->type,
            'reminder_offsets' => $request->reminder_offsets ?: [60],
            'location' => $request->location,
            'doctor_name' => $request->doctor_name,
            'description' => $request->description,
            'notify_user_1' => $request->boolean('notify_user_1', true),
            'notify_user_2' => $request->boolean('notify_user_2', true),
        ]);

        return response()->json(['appointment' => $appointment], 201);
    }

    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->authorize('view', $appointment);
        return response()->json(['appointment' => $appointment]);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->authorize('update', $appointment);

        $request->validate([
            'title' => 'string|max:255',
            'appointment_date' => 'date',
            'appointment_time' => 'nullable|date_format:H:i',
            'type' => 'nullable|in:echo,blood_test,consult,ponction,transfert,other',
            // Minutes before the appointment - can have several reminders
            // (e.g. 24h before AND 12h before), not just one.
            'reminder_offsets' => 'array',
            'reminder_offsets.*' => 'integer|min:0',
            'location' => 'nullable|string',
            'doctor_name' => 'nullable|string',
            'description' => 'nullable|string',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ]);

        $appointment->update($request->all());
        return response()->json(['appointment' => $appointment]);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted']);
    }

    public function markComplete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $this->authorize('update', $appointment);
        $appointment->update(['completed' => true]);
        return response()->json(['appointment' => $appointment]);
    }
}
