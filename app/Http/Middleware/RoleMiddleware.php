<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // API-friendly: If not logged in → return JSON, not redirect
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Check if the user has required role
        if (Auth::user()->role !== $role) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Role matched → allow request
        return $next($request);
    }
}
