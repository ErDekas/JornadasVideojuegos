<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminAttendeeController extends Controller
{
    /**
     * Method to get the list of attendees
     */
    public function listAttendees()
    {
        $attendees = User::where('is_admin', false)->get();

        return response()->json([
            'data_count' => $attendees->count(),
            'message' => 'La lista de asistentes ha sido obtenida con éxito',
            'attendees' => $attendees
        ]);
    }
}
