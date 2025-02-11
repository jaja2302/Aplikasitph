<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');

        if ($apiKey !== config('app.api_key')) {
            return response()->json([
                'message' => 'Invalid API Key'
            ], 401);
        }

        return $next($request);
    }
}
