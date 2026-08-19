<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class PimpinanMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check() || \Illuminate\Support\Facades\Auth::user()->role !== 'pimpinan') {
            return redirect('/pimpinan/login');
        }

        // Prevent caching for pimpinan routes to avoid back button issues
        return $next($request)
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}
