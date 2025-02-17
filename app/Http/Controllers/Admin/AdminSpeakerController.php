<?php

namespace App\Http\Controllers\Admin;

use App\Services\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminSpeakerController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of speakers.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $response = $this->apiService->get('/speakers');

        if (empty($response)) {
            return back()->with('error', 'No se pudieron cargar los ponentes');
        }

        $ponentes = $response['speakers'];

        return view('admin.speakers.index', compact('ponentes'));
    }

    /**
     * Show the form for creating a new speaker.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.speakers.create');
    }

    /**
     * Store a newly created speaker in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable',
            'exprestise_areas' => 'nullable|array'
        ]);

        // Manejar la subida de la imagen
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('speakers', 'public');
            $validated['photo_url'] = asset('storage/' . $path);
        }

        $response = $this->apiService->post("/speakers", $validated);

        if ($response['success'] ?? false) {
            return redirect()->route('admin.speakers.index')
                ->with('success', 'Ponente creado exitosamente');
        }

        return back()->with('error', 'Error al crear el ponente')
            ->withInput();
    }

    /**
     * Display the specified speaker.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ponente = $this->apiService->get("/speakers/{$id}");

        if (empty($ponente)) {
            return back()->with('error', 'No se pudo cargar el ponente');
        }

        return view('admin.speakers.show', ['ponente' => $ponente]);
    }

    /**
     * Show the form for editing the specified speaker.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $response = $this->apiService->get("/speakers/{$id}");

        if (!isset($response['speaker'])) { // Ajusta la clave según la estructura de la API
            return back()->with('error', 'No se pudo cargar el ponente');
        }

        $ponente = $response['speaker']; // Extraer los datos reales del ponente

        return view('admin.speakers.edit', compact('ponente'));
    }


    /**
     * Update the specified speaker in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'expertise_areas' => 'nullable|string', // Cambiamos de array a string porque vendrá como texto
        'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'social_links' => 'nullable'
    ]);

    // Convertir expertise_areas de string a array
    if ($request->filled('expertise_areas')) {
        $validated['expertise_areas'] = explode(',', $request->input('expertise_areas'));
        $validated['expertise_areas'] = array_map('trim', $validated['expertise_areas']); // Eliminar espacios extra
    }

    // Manejar la subida de la nueva imagen
    if ($request->hasFile('photo_url')) {
        $path = $request->file('photo_url')->store('speakers', 'public');
        $validated['photo_url'] = asset('storage/' . $path);
    }

    $response = $this->apiService->put("/speakers/{$id}", $validated);
    if ($response['message']=="El ponente ha sido actualizado correctamente" ?? false) {
        return redirect()->route('admin.speakers.index', $id)
            ->with('success', 'Ponente actualizado exitosamente');
    }

    return back()->with('error', 'Error al actualizar el ponente')
        ->withInput();
}


    /**
     * Remove the specified speaker from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->apiService->delete("/speakers/{$id}");

        if ($response['success'] ?? false) {
            return redirect()->route('admin.speakers.index')
                ->with('success', 'Ponente eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el ponente');
    }

    /**
     * Export speakers data to CSV/Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $response = $this->apiService->get("/speakers/export");

        if (empty($response) || !isset($response['file_path'])) {
            return back()->with('error', 'Error al exportar los datos de los ponentes');
        }

        return response()->download($response['file_path']);
    }
}
