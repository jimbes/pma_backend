<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Couple;
use App\Models\CoupleInvitation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('is_admin', false)->count();
        $totalCouples = Couple::count();
        $activeCouples = Couple::whereHas('users', function ($q) {
            $q->where('is_admin', false);
        })->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalCouples' => $totalCouples,
            'activeCouples' => $activeCouples,
        ]);
    }

    public function users()
    {
        $users = User::where('is_admin', false)
            ->with('couple')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', ['users' => $users]);
    }

    public function couples()
    {
        $couples = Couple::with(['users' => function ($q) {
                $q->where('is_admin', false);
            }])
            ->withCount(['appointments', 'medications', 'journeyStages'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.couples', ['couples' => $couples]);
    }

    public function coupleDetail(Couple $couple)
    {
        $couple->load([
            'users' => function ($q) {
                $q->where('is_admin', false);
            },
            'appointments' => function ($q) {
                $q->orderBy('appointment_date', 'desc');
            },
            'medications.schedules.journeyStage',
            'journeyStages' => function ($q) {
                $q->orderBy('order');
            },
            'invitations' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
        ]);

        return view('admin.couple-detail', ['couple' => $couple]);
    }

    public function deleteInvitation(CoupleInvitation $invitation)
    {
        $coupleId = $invitation->couple_id;
        $invitation->delete();

        return redirect()->route('admin.couple-detail', $coupleId)
            ->with('success', 'Invitation supprimée.');
    }

    public function deleteUser(User $user)
    {
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        $couple = $user->couple;

        $user->delete();

        if ($couple && $couple->users()->count() === 0) {
            $couple->delete();
        }

        return redirect()->back()->with('success', 'User and all associated data deleted successfully.');
    }

    public function usersApi()
    {
        $users = User::where('is_admin', false)
            ->with('couple')
            ->select(['id', 'name', 'email', 'couple_id', 'created_at'])
            ->get();

        return response()->json(['users' => $users]);
    }

    public function deleteUserApi(User $user)
    {
        if ($user->is_admin) {
            return response()->json(['message' => 'Cannot delete admin users.'], 403);
        }

        $couple = $user->couple;
        $user->delete();

        if ($couple && $couple->users()->count() === 0) {
            $couple->delete();
        }

        return response()->json(['message' => 'User deleted successfully.']);
    }

    public function stats()
    {
        $totalUsers = User::where('is_admin', false)->count();
        $totalCouples = Couple::count();
        $totalAppointments = \App\Models\Appointment::count();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalCouples' => $totalCouples,
            'totalAppointments' => $totalAppointments,
        ]);
    }
}
