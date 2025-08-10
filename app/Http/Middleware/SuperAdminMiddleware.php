<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        if (!auth()->user()->biodata) {
            return redirect()->route('dashboard')
                ->with('error', 'Super Admin harus memiliki data biodata terlebih dahulu untuk mengakses admin panel.');
        }

        return $next($request);
    }
}
