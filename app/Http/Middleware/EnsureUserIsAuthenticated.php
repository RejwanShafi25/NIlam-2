<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            // If it's an API request, return JSON response
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Authentication required to access auction platform.'], 401);
            }
            
            // For web requests, redirect to home page with message
            return redirect()->route('home')->with('message', 'Please login or register to access the auction platform.');
        }

        return $next($request);
    }
}
