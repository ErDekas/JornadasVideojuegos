<?php

namespace App\Http\Controllers\Admin;

use App\Services\ApiService;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of all events with advanced filtering options.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'type' => $request->type
        ];

        $response = $this->apiService->get('/events', $filters);

        if (empty($response)) {
            return back()->with('error', 'No se pudieron cargar los eventos');
        }

        $events = $response['events']; // Extraer solo el array de eventos

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $response = $this->apiService->get('/speakers');
        $ponentes = $response['speakers']; // Extraer solo el array de speakers
        return view('admin.events.create', compact('ponentes'));
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
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_attendees' => $request->max_attendees,
            'current_attendees' => $request->current_attendees,
            'location' => $request->location,
            'speakers' => $request->speakers // Asegúrate de que el campo 'speakers' esté en el formulario
        ]);

        if ($response['success']) {
            return redirect()->route('admin.events.index')
                ->with('success', 'Evento creado exitosamente');
        }

        return back()->with('error', 'Error al crear el evento')
            ->withInput();
    }
    /**
     * Display the specified event with detailed admin information.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        $registrations = $this->apiService->get("/events/{$id}/registrations");
        $attendees = $this->apiService->get("/events/{$id}/attendees"); // Asegúrate de que este endpoint exista

        return view('admin.events.show', [
            'event' => $event,
            'registrations' => $registrations,
            'attendees' => $attendees
        ]);
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $response = $this->apiService->get("/events/{$id}");
        $speakers = $this->apiService->get('/speakers');

        $speakers = $response['speakers'] ?? [];
        $event = $response['event']; // Extraer los datos reales del evento

        return view('admin.events.edit', [
            'event' => $event,
            'speakers' => $speakers,
        ]);
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
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'max_attendees' => $request->max_attendees,
            'current_attendees' => $request->current_attendees,
            'location' => $request->location,
        ]);

        if ($response['success']) {
            return redirect()->route('admin.events.show', $id)
                ->with('success', 'Evento actualizado exitosamente');
        }

        return back()->with('error', 'Error al actualizar el evento')
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
            return redirect()->route('admin.events.index')
                ->with('success', 'Evento eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el evento');
    }

    /**
     * Manage event registrations.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function manageRegistrations($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        $registrations = $this->apiService->get("/events/{$id}/registrations");

        if (empty($event) || empty($registrations)) {
            return back()->with('error', 'No se pudieron cargar las inscripciones');
        }

        return view('admin.events.registrations', [
            'event' => $event,
            'registrations' => $registrations
        ]);
    }

    /**
     * Export event data to CSV/Excel.
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export($id)
    {
        $response = $this->apiService->get("/events/{$id}/export");

        if (empty($response) || !isset($response['file_path'])) {
            return back()->with('error', 'Error al exportar los datos del evento');
        }

        return response()->download($response['file_path']);
    }
}
