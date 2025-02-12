<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Http\Requests\SpeakerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('api.token')->except(['index', 'show']);
    }

    /**
     * Display a listing of the speakers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $speakers = $this->apiService->get('/speakers');
        return view('speakers.index', compact('speakers'));
    }

    /**
     * Show the form for creating a new speaker.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('speakers.create');
    }

    /**
     * Store a newly created speaker in storage.
     *
     * @param  \App\Http\Requests\SpeakerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpeakerRequest $request)
    {
        try {
            $data = [
                'nombre' => $request->nombre,
                'experiencia' => $request->experiencia,
                'redes_sociales' => $request->redes_sociales
            ];

            // Manejar la carga de la imagen si se proporcionó una
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('speakers', 'public');
                $data['foto'] = $path;
            }

            $response = $this->apiService->post('/speakers', $data);

            if ($response['success']) {
                return redirect()->route('speakers.index')
                               ->with('success', 'Ponente creado exitosamente');
            }

            // Si falló, eliminar la imagen si se subió
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            throw new \Exception('No se pudo crear el ponente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el ponente: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified speaker.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $speaker = $this->apiService->get("/speakers/{$id}");
        return view('speakers.show', compact('speaker'));
    }

    /**
     * Show the form for editing the specified speaker.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $speaker = $this->apiService->get("/speakers/{$id}");
        return view('speakers.edit', compact('speaker'));
    }

    /**
     * Update the specified speaker in storage.
     *
     * @param  \App\Http\Requests\SpeakerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SpeakerRequest $request, $id)
    {
        try {
            $data = [
                'nombre' => $request->nombre,
                'experiencia' => $request->experiencia,
                'redes_sociales' => $request->redes_sociales
            ];

            // Obtener información actual del ponente
            $currentSpeaker = $this->apiService->get("/speakers/{$id}");

            // Manejar la actualización de la imagen
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('speakers', 'public');
                $data['foto'] = $path;

                // Eliminar la imagen anterior si existe
                if (!empty($currentSpeaker['foto'])) {
                    Storage::disk('public')->delete($currentSpeaker['foto']);
                }
            }

            $response = $this->apiService->put("/speakers/{$id}", $data);

            if ($response['success']) {
                return redirect()->route('speakers.show', $id)
                               ->with('success', 'Ponente actualizado exitosamente');
            }

            // Si falló y se subió una nueva imagen, eliminarla
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            throw new \Exception('No se pudo actualizar el ponente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el ponente: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Remove the specified speaker from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Obtener información del ponente antes de eliminarlo
            $speaker = $this->apiService->get("/speakers/{$id}");
            
            $response = $this->apiService->delete("/speakers/{$id}");

            if ($response['success']) {
                // Eliminar la imagen si existe
                if (!empty($speaker['foto'])) {
                    Storage::disk('public')->delete($speaker['foto']);
                }

                return redirect()->route('speakers.index')
                               ->with('success', 'Ponente eliminado exitosamente');
            }

            throw new \Exception('No se pudo eliminar el ponente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el ponente: ' . $e->getMessage());
        }
    }
}