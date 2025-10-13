<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                // Menggunakan accessor 'role' yang sudah Anda buat di model User
                $role = strtolower($user->role);

                switch ($role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'mahasiswa':
                        return redirect()->route('mahasiswa.dashboard');
                    case 'bak':
                        return redirect()->route('bak.dashboard');
                    case 'dekan':
                        return redirect()->route('dekan.dashboard');
                    default:
                        // Pengalihan default jika role tidak dikenali atau '-'
                        return redirect('/login');
                }
            }
        }

        return $next($request);
    }
}
