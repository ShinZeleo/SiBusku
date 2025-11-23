<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memaksa user mengisi nomor telepon
 *
 * Middleware ini memastikan bahwa user yang sudah login memiliki
 * nomor telepon sebelum bisa mengakses fitur tertentu. Nomor telepon
 * diperlukan untuk mengirim notifikasi WhatsApp.
 *
 * Proses:
 * 1. Cek apakah user sudah login
 * 2. Cek apakah user sudah punya nomor telepon
 * 3. Cek apakah route saat ini adalah profile.edit, profile.update, atau logout
 *    (route ini diizinkan agar user bisa mengisi nomor telepon)
 * 4. Jika user belum punya nomor telepon dan bukan di route yang diizinkan,
 *    redirect ke profile.edit dengan warning message
 * 5. Jika sudah punya nomor telepon atau di route yang diizinkan, lanjutkan request
 *
 * @package App\Http\Middleware
 */
class ForcePhoneMiddleware
{
    /**
     * Handle an incoming request
     *
     * @param Request $request HTTP request
     * @param Closure $next Next middleware atau controller
     * @return Response
     *         - Redirect ke profile.edit dengan warning jika user belum punya nomor telepon
     *         - Lanjutkan request jika user sudah punya nomor telepon atau di route yang diizinkan
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() &&
            !Auth::user()->phone &&
            !in_array(Route::currentRouteName(), ['profile.edit', 'profile.update', 'logout'])) {
            // Jika user belum punya nomor telepon, arahkan ke halaman edit profile
            return redirect()->route('profile.edit')->with('warning', 'Silakan lengkapi nomor telepon Anda terlebih dahulu untuk melanjutkan.');
        }

        return $next($request);
    }
}
