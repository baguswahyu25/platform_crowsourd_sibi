<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('auth.login')->withErrors([
                'email' => 'Silakan masuk (login) terlebih dahulu untuk mengakses halaman ini.',
            ]);
        }

        $userRole = is_object($user->role) ? $user->role->value : (string) $user->role;

        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
