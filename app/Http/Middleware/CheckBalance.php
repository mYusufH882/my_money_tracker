<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBalance
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
            return $next($request);
        }

        $user = auth()->user();

        // If user doesn't have balance and not already on set-initial-balance page
        if (!$user->hasBalance() && !$request->routeIs('set-initial-balance')) {
            // Add flash message
            session()->flash('info', 'Silakan set saldo awal Anda terlebih dahulu untuk mulai mencatat transaksi.');

            return redirect()->route('set-initial-balance');
        }

        // If user has balance but trying to access set-initial-balance page
        if ($user->hasBalance() && $request->routeIs('set-initial-balance')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
