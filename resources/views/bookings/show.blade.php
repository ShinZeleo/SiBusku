<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">DETAIL BOOKING</h1>

            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm space-y-6">
                <!-- Booking Info -->
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Rute</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $booking->trip->route->origin_city }} → {{ $booking->trip->route->destination_city }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H.i') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Kursi</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $booking->selected_seats ?: 'Tidak ditentukan' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Total Harga</p>
                        <p class="text-2xl font-bold text-sky-600">{{ $booking->total_price_formatted }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Status Booking</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'completed' => 'bg-blue-100 text-blue-800',
                            ];
                            $statusClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $statusClass }} mt-2">
                            {{ strtoupper($booking->status) }}
                        </span>
                    </div>
                </div>

                <!-- WhatsApp Notification Status -->
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Notifikasi</h2>

                    @php
                        $waLog = $booking->latestWhatsappLog;
                    @endphp

                    @if($waLog)
                        @if($waLog->status === 'sent')
                            <div class="flex items-center gap-2 text-green-600 mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="font-semibold">WhatsApp berhasil dikirim ke {{ $booking->customer_phone }}</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-yellow-600 mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-semibold">Status: {{ ucfirst($waLog->status) }}</span>
                            </div>
                        @endif

                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Pesan WA:</p>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p>- Detail booking</p>
                                <p>- Nomor kursi: {{ $booking->selected_seats ?: '-' }}</p>
                                <p>- Harga: {{ $booking->total_price_formatted }}</p>
                            </div>
                            @if($waLog->message)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-500 mb-1">Pesan lengkap:</p>
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $waLog->message }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <p class="text-sm text-yellow-800">Belum ada notifikasi WhatsApp yang dikirim.</p>
                        </div>
                    @endif
                </div>

                <!-- Status Log Timeline (Admin Only) -->
                @if(auth()->user()->isAdmin() && $booking->statusLogs->count() > 0)
                    <div class="border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Riwayat Perubahan Status</h2>
                        <div class="space-y-4">
                            @foreach($booking->statusLogs as $log)
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-2 h-2 rounded-full bg-sky-600 mt-1"></div>
                                        @if(!$loop->last)
                                            <div class="w-px h-full bg-gray-200 mt-2"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-4">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm font-semibold text-gray-900">
                                                @if($log->status_lama)
                                                    {{ ucfirst($log->status_lama) }} → {{ ucfirst($log->status_baru) }}
                                                @else
                                                    Status: {{ ucfirst($log->status_baru) }}
                                                @endif
                                            </p>
                                            <span class="text-xs text-gray-500">
                                                {{ $log->created_at->format('d M Y H:i') }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600 mb-1">
                                            Oleh: {{ $log->user->name }}
                                        </p>
                                        @if($log->keterangan)
                                            <p class="text-sm text-gray-700 mt-1">{{ $log->keterangan }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.bookings.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition">
                            Kembali
                        </a>
                        <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition">
                            Edit Booking
                        </a>
                    @else
                        <a href="{{ route('user.bookings.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition">
                            Kembali ke Riwayat
                        </a>
                    @endif

                    @if($booking->status === 'confirmed')
                        <a
                            href="{{ route('bookings.ticket', $booking->id) }}"
                            target="_blank"
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition inline-flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download E-Ticket
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>