<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Card -->
            <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-lg text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-100 mb-6">
                    <svg class="h-12 w-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Berhasil!</h1>
                <p class="text-lg text-gray-600 mb-6">Terima kasih telah melakukan booking di SIBUSKU</p>

                <!-- Booking Code -->
                <div class="bg-slate-900 rounded-xl p-6 mb-6">
                    <p class="text-sm text-slate-400 mb-2">Kode Booking</p>
                    <p class="text-3xl font-bold text-white tracking-wider">{{ $bookingCode }}</p>
                </div>

                <!-- Info Booking -->
                <div class="bg-gray-50 rounded-xl p-6 mb-6 text-left space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Rute</span>
                        <span class="font-semibold text-gray-900">
                            {{ $booking->trip->route->origin_city }} → {{ $booking->trip->route->destination_city }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tanggal & Waktu</span>
                        <span class="font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }},
                            {{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Kursi</span>
                        <span class="font-semibold text-gray-900">
                            {{ $booking->bookingSeats->pluck('seat_number')->join(', ') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                        <span class="text-gray-600">Total Harga</span>
                        <span class="text-2xl font-bold text-sky-600">{{ $booking->total_price_formatted }}</span>
                    </div>
                </div>

                <!-- WhatsApp Status -->
                @php
                    $waLog = $booking->latestWhatsappLog;
                @endphp
                @if($waLog && $waLog->status === 'sent')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-center gap-2 text-emerald-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-semibold">WhatsApp terkirim ke {{ $booking->customer_phone }}</span>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-center gap-2 text-yellow-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">Notifikasi WhatsApp sedang diproses</span>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if($booking->status === 'confirmed')
                        <a
                            href="{{ route('bookings.ticket', $booking->id) }}"
                            target="_blank"
                            class="inline-flex items-center justify-center px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download E-Ticket PDF
                        </a>
                    @endif
                    <a
                        href="{{ route('user.bookings.index') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition"
                    >
                        Lihat Riwayat Booking
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

