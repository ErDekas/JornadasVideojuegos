<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('api.token')->except(['index', 'show']);
    }

    public function index()
    {
        $events = $this->apiService->get('/events');
        //dd($events);
        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        return view('events.show', compact('event'));
    }


    public function register($id, Request $request)
    {
        // Despues cambiarlo al modelo
        $request->validate([
            'type' => 'required|in:presential,virtual,free',
            'is_student' => 'required|boolean',
        ]);

        $event = $this->apiService->get("/events/{$id}");
        
        // Verificar que no exceda el límite de registros (5 conferencias, 4 talleres)
        $userRegistrations = $this->apiService->get("/users/registrations");
        
        if ($event['type'] === 'conference' && $userRegistrations['conference_count'] >= 5) {
            return back()->with('error', 'Has alcanzado el límite de conferencias permitidas');
        }
        
        if ($event['type'] === 'workshop' && $userRegistrations['workshop_count'] >= 4) {
            return back()->with('error', 'Has alcanzado el límite de talleres permitidos');
        }

        // Registrar al evento
        $response = $this->apiService->post("/events/{$id}/store", [
            'type' => $request->type,
            'is_student' => $request->is_student,
        ]);

        if ($response['success']) {
            // Si no es registro gratuito redirigir a proceso de pago(Cambiar por el tipo correcto que no sea gratuito)
            if ($request->type !== 'free') {
                return redirect()->route('payment.process', ['registration_id' => $response['registration_id']]);
            }
            //Redirigir si no es gratuito
            return redirect()->route('events.registration.success', ['id' => $response['registration_id']])
                           ->with('success', 'Registro completado con éxito');
        }
        // Mensaje de error si hay algun fallo
        return back()->with('error', 'No se pudo completar el registro');
    }

    /**
     * Method to show the form for resgistration in a event
     * @var id with the id of the event to registration
     */
    public function showRegistrationForm($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        return view('events.register', compact('event'));
    }

    /**
     * Method tha redirects to the success page
     * @var id with the registration to see her details
     */
    public function registrationSuccess($registrationId)
    {
        $registration = $this->apiService->get("/registrations/{$registrationId}");
        return view('events.registration-success', compact('registration'));
    }

    /**
     * Method to cancel a registration
     * @var id of the registration that it cancel
     */
    public function cancelRegistration($id)
    {
        $response = $this->apiService->delete("/registrations/{$id}");

        if ($response['success']) {
            return back()->with('success', 'Registro cancelado con éxito');
        }

        return back()->with('error', 'No se pudo cancelar el registro');
    }

}