<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('api.token');
    }

    public function process($registrationId)
    {
        $registration = $this->apiService->get("/registrations/{$registrationId}");
        return view('payment.process', compact('registration'));
    }

    public function callback(Request $request)
    {
        $payment = $this->apiService->post('/payments/callback', $request->all());
        return redirect()->route('registration.success', $payment['registration_id']);
    }
}