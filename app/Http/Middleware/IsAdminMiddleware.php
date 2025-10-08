<?php

namespace App\Http\Middleware;

// 1. PASTIKAN BARIS INI ADA
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 2. UBAH BARIS IF MENJADI SEPERTI INI
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // Jika ya, izinkan masuk
        }

        // Jika tidak, tendang dengan error 403 (Forbidden)
        abort(403, 'ANDA TIDAK PUNYA AKSES ADMIN.');
    }
}
