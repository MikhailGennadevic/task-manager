<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJsonRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isJson() && !$request->wantsJson()) {
            return response()->json([
                'error' => 'Invalid request format',
                'message' => 'Request must be in JSON format'
            ], 415); // 415 Unsupported Media Type
        }
        return $next($request);
    }
}