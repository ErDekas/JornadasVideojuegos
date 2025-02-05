<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
        return Http::withHeaders($this->getHeaders())
            ->put($this->baseUrl . $endpoint, $data)
            ->throw()
            ->json();
    }

    public function delete($endpoint)
    {
        return Http::withHeaders($this->getHeaders())
            ->delete($this->baseUrl . $endpoint)
            ->throw()
            ->json();
    }
}