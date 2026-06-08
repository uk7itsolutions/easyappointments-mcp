<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        $installed = file_exists(storage_path('installed'));

        if (!$installed && !$request->routeIs('installer.*')) {
            return redirect()->route('installer.show');
        }

        if ($installed && $request->routeIs('installer.*')) {
            return response()->json(['error' => 'Already installed.'], 403);
        }

        return $next($request);
    }
}
