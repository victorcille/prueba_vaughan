<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        // de momento para hacer pruebas
        if ($token && $token === '[]{}()') {
            return $next($request);
        }

        return response()->json([
            'error' => 'Unauthorized'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
