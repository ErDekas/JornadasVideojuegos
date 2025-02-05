<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $speakers = $this->apiService->get('/speakers');
        return view('speakers.index', compact('speakers'));
    }

    public function show($id)
    {
        $speaker = $this->apiService->get("/speakers/{$id}");
        return view('speakers.show', compact('speaker'));
    }
}