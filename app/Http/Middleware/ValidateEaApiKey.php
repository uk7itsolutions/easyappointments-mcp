<?php

namespace App\Http\Middleware;

use App\Services\EasyAppointmentsClient;
use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ValidateEaApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $baseUrl = config('ea.base_url');

        if (empty($baseUrl)) {
            Log::error('EA_BASE_URL is not set. Configure it in .env and run: php artisan config:clear');

            return response()->json([
                'error' => 'Server misconfigured: EA_BASE_URL is not set. Edit .env on the server and run "php artisan config:clear".',
            ], 500);
        }

        $header = $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return response()->json([
                'error' => 'Missing or malformed Authorization header. Expected: Authorization: Bearer <ea-api-key>',
            ], 401);
        }

        $token = substr($header, 7);

        try {
            $check = Http::withToken($token)
                ->acceptJson()
                ->timeout(5)
                ->get($baseUrl.'/index.php/api/v1/settings');
        } catch (ConnectionException $e) {
            Log::error('Could not reach EA API', [
                'base_url' => $baseUrl,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => "Could not reach EasyAppointments at {$baseUrl}. Verify EA_BASE_URL and that the host is reachable from this server.",
            ], 502);
        }

        if ($check->status() === 401) {
            return response()->json([
                'error' => 'EasyAppointments rejected the API key. Verify the Bearer token matches a key in EA → Backend → Settings → API.',
            ], 401);
        }

        if ($check->failed()) {
            Log::error('EA API returned unexpected status', [
                'status' => $check->status(),
                'body' => $check->body(),
            ]);

            return response()->json([
                'error' => "EasyAppointments API returned HTTP {$check->status()}",
            ], 502);
        }

        app()->instance(EasyAppointmentsClient::class, new EasyAppointmentsClient($token));

        return $next($request);
    }
}
