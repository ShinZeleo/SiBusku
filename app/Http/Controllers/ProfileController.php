<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Controller untuk mengelola profile user
 *
 * Controller ini menangani operasi CRUD profile user, termasuk
 * update informasi profile dan penghapusan akun.
 *
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profile user
     *
     * Fungsi ini menampilkan form untuk mengubah informasi profile user,
     * seperti nama, email, dan nomor telepon.
     *
     * @param Request $request HTTP request (untuk mendapatkan user yang sedang login)
     * @return View View profile.edit dengan data user
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mengupdate informasi profile user
     *
     * Fungsi ini mengupdate informasi profile user dengan validasi lengkap.
     *
     * Proses yang dilakukan:
     * 1. Validasi input menggunakan ProfileUpdateRequest
     * 2. Normalisasi nomor telepon (format: 62xxxxxxxxxxx)
     * 3. Update data user
     * 4. Jika email berubah, reset email_verified_at (user harus verifikasi lagi)
     * 5. Simpan perubahan
     *
     * Normalisasi Nomor Telepon:
     * - Hapus semua karakter non-numeric
     * - Jika dimulai dengan '0', ganti dengan '62' (kode negara Indonesia)
     * - Contoh: "081234567890" -> "6281234567890"
     *
     * @param ProfileUpdateRequest $request Request yang sudah divalidasi
     * @return RedirectResponse
     *         - Redirect ke profile.edit dengan success message jika berhasil
     *         - Redirect ke profile.edit dengan error message jika gagal
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Validasi sudah dilakukan di ProfileUpdateRequest
            $validated = $request->validated();

            // Normalisasi nomor telepon sebelum fill
            if (isset($validated['phone'])) {
                $phone = preg_replace('/[^0-9]/', '', $validated['phone']);
                if (str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                }
                $validated['phone'] = $phone;
            }

            $user->fill($validated);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('profile.edit')
                ->with('status', 'profile-updated')
                ->with('success', 'Profile berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Profile update failed', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return Redirect::route('profile.edit')
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui profile. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus akun user
     *
     * Fungsi ini menghapus akun user secara permanen setelah validasi password.
     *
     * Proses yang dilakukan:
     * 1. Validasi password user (harus sesuai dengan password saat ini)
     * 2. Logout user dari session
     * 3. Hapus user dari database
     * 4. Invalidate dan regenerate session token
     * 5. Redirect ke home page
     *
     * WARNING: Operasi ini tidak bisa di-undo. Semua data user akan dihapus.
     *
     * @param Request $request HTTP request dengan password untuk konfirmasi
     * @return RedirectResponse Redirect ke home page
     * @throws \Illuminate\Validation\ValidationException Jika password tidak valid
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
