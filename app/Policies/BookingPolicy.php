<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // User bisa lihat booking mereka sendiri, admin lihat semua
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        // User hanya bisa lihat booking miliknya, admin bisa lihat semua
        return $user->isAdmin() || $booking->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->isAdmin(); // Hanya user biasa yang bisa booking
    }

    /**
     * Determine whether the user can update the model.
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
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Hanya admin yang bisa delete
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // User bisa cancel booking mereka sendiri jika masih pending
        return $booking->user_id === $user->id && $booking->status === 'pending';
    }
}
