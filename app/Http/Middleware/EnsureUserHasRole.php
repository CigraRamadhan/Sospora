<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isActive()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda berstatus nonaktif. Silakan hubungi pihak Dispora Kota Bandung.',
            ]);
        }

        if (! empty($roles)) {
            $hasRole = false;
            foreach ($roles as $role) {
                // Support comma-separated e.g. "admin,superadmin"
                $subRoles = explode(',', $role);
                foreach ($subRoles as $subRole) {
                    $subRole = trim($subRole);
                    if ($user->role === $subRole || $user->hasRole($subRole)) {
                        $hasRole = true;
                        break 2;
                    }
                }
            }

            if (! $hasRole) {
                abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
            }
        }

        return $next($request);
    }
}
