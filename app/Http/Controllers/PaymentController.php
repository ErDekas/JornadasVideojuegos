<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;

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

    public function createPayment($price)
{
    $user = Session::get('user');
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
                "return_url" => url('/paypal/success') . '?user_id=' . $user['id']  // Añadimos el user_id
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
        Log::error('Error al crear el pedido de PayPal: ' . $e->getMessage());
        return redirect()->route('paypal.cancel')->with('error', 'Error al conectar con PayPal.');
    }
}

public function capturePayment(Request $request)
{
    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));
    $provider->getAccessToken();

    $paypalToken = $request->query('token');
    $userId = $request->query('user_id');  // Obtenemos el user_id

    if (!$paypalToken || !$userId) {
        return redirect()->route('paypal.cancel')->with('error', 'Información de pago inválida.');
    }

    try {
        $response = $provider->capturePaymentOrder($paypalToken);
        Log::info('Respuesta de PayPal:', ['response' => $response]);

        if (isset($response['response']) && $response['response']['status'] == 'COMPLETED') {
            try {
                // En lugar de obtener el usuario de la sesión, lo obtenemos de la API
                $userData = $this->apiService->get("/users/{$userId}");
                
                if (!isset($userData['users'])) {
                    throw new Exception('Usuario no encontrado.');
                }

                // Actualizar el estado de is_first_login
                $updateResponse = $this->apiService->put("/user/updateFirstLogin/{$userId}", [
                    'is_first_login' => false
                ]);

                if ($updateResponse) {
                    // Volver a obtener los datos actualizados del usuario
                    $newUserData = $this->apiService->get("/users/{$userId}");
                    Session::put('user', $newUserData['users']);
                    
                    return redirect()->route('home')->with('success', 'Pago procesado correctamente.');
                }

                throw new Exception('Error al actualizar el estado de pago.');
            } catch (Exception $e) {
                Log::error("Error en actualización post-pago: " . $e->getMessage());
                return redirect()->route('paypal.cancel')->with('error', 'Error al actualizar el estado del pago.');
            }
        }

        return redirect()->route('home')->with('error', 'El pago no se completó.');
    } catch (\Exception $e) {
        Log::error('Error al capturar el pago de PayPal: ' . $e->getMessage());
        return redirect()->route('home')->with('error', 'Error al procesar el pago.');
    }
}
}
