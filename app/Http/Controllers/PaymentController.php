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

    public function createPayment($price){
        
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
    
        try {
            $paypalToken = $provider->getAccessToken();
            if (!$paypalToken) {
                return redirect()->route('paypal.cancel')->with('error', 'No se pudo obtener el token de PayPal.');
            }
    
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => $price
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
            }
    
            return redirect()->route('paypal.cancel')->with('error', 'No se pudo procesar el pago.');
        } catch (\Exception $e) {
            return redirect()->route('paypal.cancel')->with('error', 'Error al conectar con PayPal.');
        }
    }
    

    public function capturePayment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $paypalToken = $request->query('token');
        if (!$paypalToken) {
            return redirect()->route('paypal.cancel')->with('error', 'Token inválido.');
        }

        try {
            $response = $provider->capturePaymentOrder($paypalToken);
    
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                return redirect()->route('home')->with('success', 'Pago exitoso.');
            } else {
                return redirect()->route('paypal.cancel')->with('error', 'El pago no se completó.');
            }
        } catch (\Exception $e) {
            return redirect()->route('paypal.cancel')->with('error', 'Error al procesar el pago.');
        }
    }
}