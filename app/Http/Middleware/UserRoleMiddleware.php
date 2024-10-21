<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    // Add custom parameter $role which pass from Route.php
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan pengguna terautentikasi
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $user = auth()->user();

        // Cek apakah email sudah diverifikasi
        if ($user->email_verified_at === null) {
            return redirect('/email/verify')->with('error', 'Anda harus memverifikasi email Anda untuk mengakses halaman ini.');
        }

        // Periksa apakah pengguna memiliki peran yang sesuai
        // Biarkan akses jika pengguna adalah admin atau jika role yang diminta adalah user
        if ($user->role !== $role && $role !== 'user') {
            Log::info('User role tidak sesuai: ' . $user->role);
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Lanjutkan ke request berikutnya jika peran sesuai
        return $next($request);
    }
}
