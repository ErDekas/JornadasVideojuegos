<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class AdminPaymentController extends Controller
{
    /**
     * Method to get the list of payments
     */
    public function listPayments()
    {
        $payments = Payment::all();

        return response()->json([
            'data_count' => $payments->count(),
            'message' => 'La lista de pagos obtenida con éxito',
            'payments' => $payments
        ]);
    }
}
