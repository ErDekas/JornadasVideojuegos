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
        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = $this->apiService->get("/events/{$id}");
        return view('events.show', compact('event'));
    }
}