<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class EasyAppointmentsClient
{
    public function __construct(private readonly string $token) {}

    public function get(string $path, array $params = []): mixed
    {
        return $this->request('get', $path, ['query' => $params]);
    }

    public function post(string $path, array $data): array
    {
        return $this->request('post', $path, ['json' => $data]);
    }

    public function put(string $path, array $data): array
    {
        return $this->request('put', $path, ['json' => $data]);
    }

    public function delete(string $path): void
    {
        $this->request('delete', $path);
    }

    private function request(string $method, string $path, array $options = []): mixed
    {
        $url = config('ea.base_url') . '/api/v1/' . ltrim($path, '/');

        $response = Http::withToken($this->token)
            ->acceptJson()
            ->$method($url, $options['query'] ?? $options['json'] ?? []);

        if ($response->failed()) {
            throw new RuntimeException("EA API error {$response->status()}: {$response->body()}");
        }

        return $response->status() === 204 ? null : $response->json();
    }
}
