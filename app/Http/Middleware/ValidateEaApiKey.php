<?php

namespace App\Http\Middleware;

use App\Services\EasyAppointmentsClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ValidateEaApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');

        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = substr($header, 7);

        $check = Http::withToken($token)
            ->acceptJson()
            ->timeout(5)
            ->get(config('ea.base_url') . '/api/v1/settings');

        if ($check->failed()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Bind the authenticated client so tools can inject it
        app()->instance(EasyAppointmentsClient::class, new EasyAppointmentsClient($token));

        return $next($request);
    }
}
