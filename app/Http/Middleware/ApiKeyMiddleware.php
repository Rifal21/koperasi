<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY'); // ambil dari header

        if ($apiKey !== config('apiKey.key')) {
            return response()->json([
                'message' => 'Unauthorized: Invalid API Key',
            ], 401);
        }

        return $next($request);
    }
}
