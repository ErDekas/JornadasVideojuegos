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
        try {
            $provider = new PayPalClient;
            
            // Verifica que la configuración existe
            $config = config('paypal');
            if (empty($config)) {
                Log::error('Configuración de PayPal no encontrada');
                return redirect()->route('paypal.cancel')
                               ->with('error', 'Error en la configuración de PayPal.');
            }
    
            $provider->setApiCredentials($config);
            $token = $provider->getAccessToken();
            
            if (!$token) {
                Log::error('No se pudo obtener el token de PayPal');
                return redirect()->route('paypal.cancel')
                               ->with('error', 'Error de autenticación con PayPal.');
            }
    
            // Guarda el ID del usuario en la sesión específicamente para PayPal
            if (Session::has('user')) {
                Session::put('paypal_user_id', Session::get('user')['id']);
            }
    
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($price, 2, '.', '') // Asegura formato correcto
                        ]
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('paypal.cancel'),
                    "return_url" => route('paypal.success')
                ]
            ]);
    
            Log::info('Respuesta de creación de orden PayPal:', ['response' => $response]);
    
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return redirect()->away($link['href']);
                    }
                }
            }
    
            throw new \Exception('No se encontró el enlace de aprobación en la respuesta de PayPal');
        } catch (\Exception $e) {
            Log::error('Error al crear el pedido de PayPal: ' . $e->getMessage());
            return redirect()->route('paypal.cancel')
                           ->with('error', 'Error al conectar con PayPal: ' . $e->getMessage());
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
