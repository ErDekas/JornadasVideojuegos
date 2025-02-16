<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Registration;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    /**
     * Method to get the statistics
     */
    public function getStatistics()
    {
        $totalUsers = User::count();
        $totalAttendees = User::where('is_admin', false)->count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalPayments = Payment::sum('amount');
        $totalRegistrations = Registration::count();

        return response()->json([
            'data_count' => 1,
            'message' => 'Las estadísticas han sido obtenidas con éxito',
            'total_users' => $totalUsers,
            'total_attendees' => $totalAttendees,
            'total_admins' => $totalAdmins,
            'total_payments' => $totalPayments,
            'total_registrations' => $totalRegistrations
        ], 200);
    }
    
}
