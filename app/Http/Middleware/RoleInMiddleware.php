<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleInMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();
        $userRoleName = $user && $user->role ? $user->role->nama_role : null;

        foreach ($roles as $roleName) {
            if ($userRoleName && strcasecmp($userRoleName, $roleName) === 0) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak.');
    }
}


