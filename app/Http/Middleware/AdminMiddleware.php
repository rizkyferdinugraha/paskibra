<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
        }

        if (!$user->biodata) {
            return redirect()->route('dashboard')
                ->with('error', 'Admin harus memiliki data biodata terlebih dahulu untuk mengakses admin panel.');
        }

        return $next($request);
    }
}


