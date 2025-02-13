<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Http\Requests\EventRequest;
use App\Http\Requests\SeleccionEventsRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware(\App\Http\Middleware\CheckApiToken::class)->except(['index', 'show']);
    }

    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $events = $this->apiService->get('/events');
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $ponentes = $this->apiService->get('/ponentes');
        return view('events.create', compact('ponentes'));
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \App\Http\Requests\EventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $response = $this->apiService->post("/events", [
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'ponente_id' => $request->ponente_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        if ($response['success']) {
            return redirect()->route('events.index')
                           ->with('success', 'Evento creado exitosamente');
        }

        return back()->with('error', 'No se pudo crear el evento')
                    ->withInput();
    }

    /**
     * Display the specified event.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        $ponentes = $this->apiService->get('/ponentes');
        return view('events.edit', compact('event', 'ponentes'));
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \App\Http\Requests\EventRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, $id)
    {
        $response = $this->apiService->put("/events/{$id}", [
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'ponente_id' => $request->ponente_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
        ]);

        if ($response['success']) {
            return redirect()->route('events.show', $id)
                           ->with('success', 'Evento actualizado exitosamente');
        }

        return back()->with('error', 'No se pudo actualizar el evento')
                    ->withInput();
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->apiService->delete("/events/{$id}");

        if ($response['success']) {
            return redirect()->route('events.index')
                           ->with('success', 'Evento eliminado exitosamente');
        }

        return back()->with('error', 'No se pudo eliminar el evento');
    }

    /**
     * Show registration form for an event.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        return view('events.register', compact('event'));
    }


    /**
     * Register user for a single event.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register($id, Request $request)
    {
        $event = $this->apiService->get("/events/{$id}");
        // Esta ruta hay que cambiarla
        $userRegistrations = $this->apiService->get("/users/registrations");
        
        if ($event['type'] === 'conference' && $userRegistrations['conference_count'] >= 5) {
            return back()->with('error', 'Se ha alcanzado el límite de conferencias');
        }
        
        if ($event['type'] === 'workshop' && $userRegistrations['workshop_count'] >= 4) {
            return back()->with('error', 'Has alcanzado el límite de talleres permitidos');
        }

        // Creo que es el evento de guardar el registro del usuario a un evento
        // hay que cambiarlo por la ruta correcta con los parametrod correctos
        $response = $this->apiService->post("/events/{$id}/store", [
            'type' => $request->type,
            'is_student' => $request->is_student,
        ]);

        if ($response['success']) {
            // cambiarlo por el tipo de registro del usuario
            if ($request->type !== 'free') {
                return redirect()->route('payment.process', ['registration_id' => $response['registration_id']]);
            }
            // Creo que hay que cambiarlo
            return redirect()->route('events.registration.success', ['id' => $response['registration_id']])
                           ->with('success', 'Registro completado con éxito');
        }

        return back()->with('error', 'No se pudo completar el registro');
    }

    /**
     * Display registration success page.
     *
     * @param  int  $registrationId
     * @return \Illuminate\View\View
     */
    public function registrationSuccess($registrationId = null)
    {
        if ($registrationId) {
            $registration = $this->apiService->get("/registrations/{$registrationId}");
            return view('events.registration-success', compact('registration'));
        }
        
        return view('events.registration-success');
    }

    /**
     * Cancel event registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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