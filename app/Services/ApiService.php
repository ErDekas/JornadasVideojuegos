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

    public function postWithFile($endpoint, $file, $data = [])
    {
        // Crear cliente HTTP con los headers correctos
        $client = new \GuzzleHttp\Client([
            'headers' => array_merge($this->getHeaders(), [
                'Accept' => 'application/json',
                'Content-Type' => 'multipart/form-data'
            ])
        ]);

        // Preparar el multipart
        $multipart = [];

        // Agregar el archivo
        $multipart[] = [
            'name' => 'photo_url',
            'contents' => fopen($file->path(), 'r'),
            'filename' => $file->getClientOriginalName(),
            'headers' => [
                'Content-Type' => $file->getMimeType()
            ]
        ];

        // Agregar el resto de los datos
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $index => $item) {
                    $multipart[] = [
                        'name' => $key . '[]',
                        'contents' => $item
                    ];
                }
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value
                ];
            }
        }

        Log::info('Enviando request multipart:', [
            'url' => $this->baseUrl . $endpoint,
            'multipart_structure' => $multipart
        ]);

        try {
            // Realizar la petición
            $response = $client->request('POST', $this->baseUrl . $endpoint, [
                'multipart' => $multipart
            ]);

            // Obtener y loguear la respuesta
            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta de la API:', $responseBody);

            return $responseBody;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::error('Error en la petición a la API:', [
                'message' => $e->getMessage(),
                'request' => [
                    'url' => $this->baseUrl . $endpoint,
                    'headers' => $this->getHeaders(),
                    'multipart_count' => count($multipart)
                ]
            ]);

            throw $e;
        }
    }
    public function putWithFile($endpoint, $file, $data = [])
    {
        // Crear cliente HTTP con los headers correctos
        $client = new \GuzzleHttp\Client([
            'headers' => array_merge($this->getHeaders(), [
                'Accept' => 'application/json',
                'Content-Type' => 'multipart/form-data'
            ])
        ]);

        // Preparar el multipart
        $multipart = [];

        // Agregar el archivo
        $multipart[] = [
            'name' => 'photo_url',
            'contents' => fopen($file->path(), 'r'),
            'filename' => $file->getClientOriginalName(),
            'headers' => [
                'Content-Type' => $file->getMimeType()
            ]
        ];

        // Agregar el método _method para simular PUT
        $multipart[] = [
            'name' => '_method',
            'contents' => 'PUT'
        ];

        // Agregar el resto de los datos
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $index => $item) {
                    $multipart[] = [
                        'name' => $key . '[]',
                        'contents' => $item
                    ];
                }
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value
                ];
            }
        }

        Log::info('Enviando request multipart update:', [
            'url' => $this->baseUrl . $endpoint,
            'multipart_structure' => $multipart
        ]);

        try {
            // Realizar la petición como POST pero simulando PUT
            $response = $client->request('POST', $this->baseUrl . $endpoint, [
                'multipart' => $multipart
            ]);

            // Obtener y loguear la respuesta
            $responseBody = json_decode($response->getBody()->getContents(), true);
            Log::info('Respuesta de la API update:', $responseBody);

            return $responseBody;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::error('Error en la petición de actualización a la API:', [
                'message' => $e->getMessage(),
                'request' => [
                    'url' => $this->baseUrl . $endpoint,
                    'headers' => $this->getHeaders(),
                    'multipart_count' => count($multipart)
                ]
            ]);

            throw $e;
        }
    }
}
