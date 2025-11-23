<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware untuk membatasi akses hanya untuk admin
 *
 * Middleware ini memastikan bahwa hanya user dengan role 'admin'
 * yang bisa mengakses route yang dilindungi.
 *
 * Proses:
 * 1. Cek apakah user sudah login
 * 2. Jika belum login, redirect ke halaman login
 * 3. Cek apakah user memiliki role 'admin'
 * 4. Jika bukan admin, return 403 Forbidden
 * 5. Jika admin, lanjutkan request
 *
 * @package App\Http\Middleware
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request
     *
     * @param Request $request HTTP request
     * @param Closure $next Next middleware atau controller
     * @return Response
     *         - Redirect ke /login jika user belum login
     *         - 403 Forbidden jika user bukan admin
     *         - Lanjutkan request jika user adalah admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admins only.');
        }

        return $next($request);
    }
}
