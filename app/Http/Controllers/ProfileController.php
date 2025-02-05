<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('api.token');
    }

    public function show()
    {
        $profile = $this->apiService->get('/profile');
        $registrations = $this->apiService->get('/profile/registrations');
        
        return view('profile.show', compact('profile', 'registrations'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ]);

        $profile = $this->apiService->put('/profile', $validated);
        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente');
    }
}