<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!JWTAuth::parseToken()->check()) {
                // Handle unauthorized access
                return response()->json([
                    'msg' => 'Unauthorized',
                    'success' => false,
                ], 401);
            }
        } catch (JWTException) {
            return response()->json([
                'msg' => 'Unauthorized',
                'success' => false,
            ], 401);
        }

        // Continue processing the request
        return $next($request);
    }
}
