<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppLog;
use Illuminate\View\View;

/**
 * Controller untuk mengelola log WhatsApp (untuk admin)
 *
 * Controller ini menampilkan semua log pengiriman WhatsApp untuk
 * keperluan monitoring dan debugging.
 *
 * @package App\Http\Controllers
 */
class WhatsAppLogController extends Controller
{
    /**
     * Menampilkan daftar semua log WhatsApp
     *
     * Fungsi ini menampilkan semua log pengiriman WhatsApp dengan pagination
     * (20 log per halaman). Data diurutkan dari yang terbaru.
     *
     * Data yang ditampilkan:
     * - Detail log (phone, message, status, sent_at, error_message)
     * - Informasi booking terkait (jika ada)
     * - Statistik: Total log, jumlah sent, jumlah failed
     *
     * Relasi yang di-load:
     * - booking.user: User yang melakukan booking
     * - booking.trip.route: Rute dari trip yang dibooking
     *
     * @return View View admin.whatsapp-logs.index dengan data logs (paginated) dan stats
     */
    public function index(): View
    {
        $logsQuery = WhatsAppLog::with(['booking.user', 'booking.trip.route'])->latest();

        $logs = $logsQuery->paginate(20);

        $stats = [
            'total' => WhatsAppLog::count(),
            'sent' => WhatsAppLog::where('status', 'sent')->count(),
            'failed' => WhatsAppLog::where('status', 'failed')->count(),
        ];

        return view('admin.whatsapp-logs.index', compact('logs', 'stats'));
    }
}
