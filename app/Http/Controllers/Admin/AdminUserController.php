<?php

namespace App\Http\Controllers\Admin;

use App\Services\ApiService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of users with filtering options.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filters = [
            'type' => $request->type,
            'verified' => $request->verified,
            'student' => $request->student
        ];

        $response = $this->apiService->get('/users', $filters);
        $users = $response; // Ya que la API devuelve directamente los usuarios
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'registration_type' => 'required|in:virtual,presential,student',
            'is_admin' => 'boolean',
            'student_verified' => 'boolean'
        ]);

        // Convertir checkbox a booleano explícito
        $validated['is_admin'] = $request->has('is_admin') ? true : false;
        
        $response = $this->apiService->post('/users', $validated);

        if (isset($response['message']) && $response['message'] === 'El usuario ha sido actualizado correctamente') {
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario creado exitosamente');
        }

        return back()->with('error', 'Error al crear el usuario: ' . ($response['message'] ?? 'Error desconocido'))
            ->withInput();
    }

    /**
     * Display the specified user with their events and registrations.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $response = $this->apiService->get("/users/{$id}");
        
        if (!isset($response['users'])) {
            return back()->with('error', 'No se pudo cargar el usuario');
        }
        
        $user = $response['users'];
        $registrations = $this->apiService->get("/users/{$id}/registrations") ?? [];
        $events = $this->apiService->get("/users/{$id}/events") ?? [];

        return view('admin.users.show', [
            'user' => $user,
            'registrations' => $registrations,
            'events' => $events
        ]);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $response = $this->apiService->get("/users/{$id}");

        if (!isset($response['users'])) {
            return back()->with('error', 'No se pudo cargar el usuario');
        }

        $user = $response['users']; // Extraer los datos reales del usuario

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Logging para saber qué usuario está haciendo la solicitud
        Log::info('Usuario autenticado:', ['user' => $id]);
        // Logging para depuración
        Log::info('Actualizando usuario ID: ' . $id, ['request_data' => $request->all()]);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'registration_type' => 'required|in:virtual,presential,student',
            'is_admin' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        // Ajustar el valor de is_admin para que sea un booleano explícito
        $validated['is_admin'] = $request->has('is_admin') ? true : false;
        
        // Si no se proporciona una nueva contraseña, eliminarla de los datos a actualizar
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // Realizar la solicitud PUT a la API
        $response = $this->apiService->put("/users/{$id}", $validated);
        
        // Logging de la respuesta
        Log::info('Respuesta API al actualizar usuario:', ['response' => $response]);

        // Verificar si la actualización fue exitosa según el formato de respuesta de la API
        if (isset($response['message']) && $response['message'] === 'El usuario ha sido actualizado correctamente') {
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario actualizado exitosamente');
        }

        return back()->with('error', 'Error al actualizar el usuario: ' . ($response['message'] ?? 'Error desconocido'))
            ->withInput();
    }

    /**
     * Remove the specified user from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = $this->apiService->delete("/users/{$id}");

        if (isset($response['message']) && $response['message'] === 'El usuario ha sido borrado correctamente') {
            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el usuario: ' . ($response['message'] ?? 'Error desconocido'));
    }

    /**
     * Verify a student user's status.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function verifyStudent($id)
    {
        $response = $this->apiService->put("/users/{$id}/verify-student", [
            'student_verified' => true
        ]);

        if (isset($response['message']) && $response['message'] === 'El usuario ha sido actualizado correctamente') {
            return back()->with('success', 'Estado de estudiante verificado exitosamente');
        }

        return back()->with('error', 'Error al verificar el estado de estudiante: ' . ($response['message'] ?? 'Error desconocido'));
    }

    /**
     * Export users data to CSV/Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $response = $this->apiService->get("/users/export");

        if (empty($response) || !isset($response['file_path'])) {
            return back()->with('error', 'Error al exportar los datos de usuarios');
        }

        return response()->download($response['file_path']);
    }

    /**
     * Get user attendance history.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function attendanceHistory($id)
    {
        $response = $this->apiService->get("/users/{$id}");
        
        if (!isset($response['users'])) {
            return back()->with('error', 'No se pudo cargar el usuario');
        }
        
        $user = $response['users'];
        $attendance = $this->apiService->get("/users/{$id}/attendance") ?? [];

        if (empty($user)) {
            return back()->with('error', 'No se pudo cargar el historial de asistencia');
        }

        return view('admin.users.attendance', [
            'user' => $user,
            'attendance' => $attendance
        ]);
    }
}