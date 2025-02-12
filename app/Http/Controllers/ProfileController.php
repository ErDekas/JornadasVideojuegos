<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\ApiService;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        //$this->middleware('api.token');
    }

    /**
     * Display the user's profile form.
     */
    public function show()
    {
        $token = Session::get('api_token');
        if (!$token) {
            return redirect()->intended(route('home', absolute: false));
        }
        
        $id = Session::get('user')['id'];
        if (!$id) {
            return redirect()->intended(route('home', absolute: false));
        }


        try {
            $response = $this->apiService->get("/users/{$id}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json'
                ]
            ]);
            //die(var_dump($response));
            if (!$response || !isset($response['users'])) {
                die("he entrado");
                return redirect()->intended(route('home', absolute: false));
            }
    
            return view('profile.show', [
                'user' => $response,
            ]);
        } catch (\Exception $e) {
            die("Error" . $e->getMessage());
            return redirect()->intended(route('home', absolute: false))
                             ->withErrors(['error' => 'No se han obtenido los datos del usuario']);
        }
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
