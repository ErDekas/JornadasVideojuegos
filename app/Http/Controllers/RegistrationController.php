<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->middleware('api.token');
    }

    public function create()
    {
        $events = $this->apiService->get('/events');
        return view('registration.create', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_type' => 'required|in:virtual,presential,free',
            'events' => 'required|array',
            'events.*' => 'exists:events,id'
        ]);

        $registration = $this->apiService->post('/registrations', $validated);

        if ($registration['payment_required']) {
            return redirect()->route('payment.process', $registration['id']);
        }

        return redirect()->route('registration.success', $registration['id']);
    }
}