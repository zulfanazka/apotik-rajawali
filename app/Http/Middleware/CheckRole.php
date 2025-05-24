<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // Menggunakan variadic parameter untuk menerima satu atau lebih role
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Dapatkan pengguna yang sedang terautentikasi
        $user = Auth::user();

        // Periksa apakah peran pengguna ada dalam daftar peran yang diizinkan
        if (!in_array($user->role, $roles)) {
            // Jika peran tidak diizinkan, redirect ke halaman dashboard dengan pesan error
            // atau tampilkan halaman 403 (Forbidden)
            // return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki peran yang sesuai.');
        }

        // Jika peran diizinkan, lanjutkan ke request berikutnya
        return $next($request);
    }
}