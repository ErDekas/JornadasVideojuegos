<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Controllers\Controller;


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
