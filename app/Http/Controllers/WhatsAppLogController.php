<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppLog;
use Illuminate\View\View;

class WhatsAppLogController extends Controller
{
    /**
     * Tampilkan daftar log WhatsApp.
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
