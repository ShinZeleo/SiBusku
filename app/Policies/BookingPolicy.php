<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

/**
 * Policy untuk mengatur authorization pada Booking
 *
 * Policy ini menentukan siapa yang bisa melakukan operasi tertentu
 * pada booking berdasarkan role dan ownership.
 *
 * @package App\Policies
 */
class BookingPolicy
{
    /**
     * Menentukan apakah user bisa melihat daftar booking
     *
     * - User biasa: Bisa melihat booking mereka sendiri
     * - Admin: Bisa melihat semua booking
     *
     * @param User $user User yang melakukan aksi
     * @return bool true jika diizinkan, false jika tidak
     */
    public function viewAny(User $user): bool
    {
        return true; // User bisa lihat booking mereka sendiri, admin lihat semua
    }

    /**
     * Menentukan apakah user bisa melihat detail booking tertentu
     *
     * - User biasa: Hanya bisa melihat booking miliknya sendiri
     * - Admin: Bisa melihat semua booking
     *
     * @param User $user User yang melakukan aksi
     * @param Booking $booking Booking yang akan dilihat
     * @return bool true jika diizinkan, false jika tidak
     */
    public function view(User $user, Booking $booking): bool
    {
        // User hanya bisa lihat booking miliknya, admin bisa lihat semua
        return $user->isAdmin() || $booking->user_id === $user->id;
    }

    /**
     * Menentukan apakah user bisa membuat booking baru
     *
     * - User biasa: Bisa membuat booking
     * - Admin: Tidak bisa membuat booking (hanya untuk monitoring)
     *
     * @param User $user User yang melakukan aksi
     * @return bool true jika diizinkan, false jika tidak
     */
    public function create(User $user): bool
    {
        return !$user->isAdmin(); // Hanya user biasa yang bisa booking
    }

    /**
     * Menentukan apakah user bisa mengupdate booking
     *
     * - Admin: Bisa mengupdate semua booking
     * - User biasa: Hanya bisa mengupdate booking miliknya sendiri
     *   yang masih berstatus 'pending'
     *
     * @param User $user User yang melakukan aksi
     * @param Booking $booking Booking yang akan diupdate
     * @return bool true jika diizinkan, false jika tidak
     */
    public function update(User $user, Booking $booking): bool
    {
        // Admin bisa update semua, user hanya bisa update miliknya jika masih pending
        if ($user->isAdmin()) {
            return true;
        }

        return $booking->user_id === $user->id && $booking->status === 'pending';
    }

    /**
     * Menentukan apakah user bisa menghapus booking
     *
     * - Admin: Bisa menghapus semua booking
     * - User biasa: Tidak bisa menghapus booking
     *
     * @param User $user User yang melakukan aksi
     * @param Booking $booking Booking yang akan dihapus
     * @return bool true jika diizinkan, false jika tidak
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Hanya admin yang bisa delete
        return $user->isAdmin();
    }

    /**
     * Menentukan apakah user bisa membatalkan booking
     *
     * - User biasa: Bisa membatalkan booking miliknya sendiri
     *   yang masih berstatus 'pending'
     * - Admin: Tidak menggunakan policy ini (bisa update status langsung)
     *
     * @param User $user User yang melakukan aksi
     * @param Booking $booking Booking yang akan dibatalkan
     * @return bool true jika diizinkan, false jika tidak
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // User bisa cancel booking mereka sendiri jika masih pending
        return $booking->user_id === $user->id && $booking->status === 'pending';
    }
}
