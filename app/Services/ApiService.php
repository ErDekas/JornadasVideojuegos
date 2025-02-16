<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api.url');
    }

    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . session('api_token'),
        ];
    }

    public function get($endpoint, $params = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . $endpoint, $params)
            ->throw()
            ->json();
    }

    public function post($endpoint, $data = [])
    {
        return Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . $endpoint, $data)
            ->throw()
            ->json();
    }

    public function put($endpoint, $data = [])
{
    $token = session('api_token');
    Log::info('Token actual en la sesión:', ['token' => $token]);
    
    $response = Http::withHeaders($this->getHeaders())
        ->put($this->baseUrl . $endpoint, $data);
    
    Log::info('Respuesta API completa:', ['response' => $response->json(), 'status' => $response->status()]);
    
    return $response->json();
}

    public function delete($endpoint)
    {
        return Http::withHeaders($this->getHeaders())
            ->delete($this->baseUrl . $endpoint)
            ->throw()
            ->json();
    }
}
