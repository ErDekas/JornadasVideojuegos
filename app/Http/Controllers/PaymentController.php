<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

use Srmklive\PayPal\Services\PayPal as PayPalClient;


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

    public function createPayment(){
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        // Poner el precio que sea
                        "value" => "10.00"
                    ]
                ]
            ],
            "application_context" => [
                "cancel_url" => url('/paypal/cancel'),
                "return_url" => url('/paypal/success')
            ]
        ]);
    
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('paypal.cancel');
        }
    }

    public function capturePayment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->query('token'));

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return "Pago exitoso. ID de transacción: " . $response['id'];
        } else {
            return "El pago no se completó.";
        }
    }
}