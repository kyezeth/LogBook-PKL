<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user account is active
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        // Check if user account is locked
        if ($user->isLocked()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda sementara terkunci. Coba lagi nanti.');
        }

        // Check if user has one of the required roles
        if (!empty($roles) && !in_array($user->role, $roles)) {
            // Redirect based on user role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('member.dashboard');
        }

        return $next($request);
    }
}
