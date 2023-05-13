<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', 'http://127.0.0.1:5174');
        $response->header('Access-Control-Allow-Headers', 'Content-Type');

        return $response;
    }
}
