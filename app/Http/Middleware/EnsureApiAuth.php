<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class EnsureApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided. Please login first.',
                'error' => 'token_missing'
            ], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token. Please login again.',
                'error' => 'token_invalid'
            ], 401);
        }

        // Set authenticated user
        auth()->setUser($accessToken->tokenable);
        $request->setUserResolver(fn() => $accessToken->tokenable);

        return $next($request);
    }
}
