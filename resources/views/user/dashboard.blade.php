<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'completed' => 'bg-blue-100 text-blue-800',
                ];
            @endphp

            <div class="space-y-8">
                <!-- Statistik -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white overflow-hidden shadow rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500/10 text-blue-600 rounded-xl p-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <p class="text-sm text-gray-500">Total Booking</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalBookings }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-emerald-500/10 text-emerald-600 rounded-xl p-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <p class="text-sm text-gray-500">Trip Mendatang</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $upcomingTrips->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500/10 text-yellow-600 rounded-xl p-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <p class="text-sm text-gray-500">Booking Aktif</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $activeBookingsCount }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-2xl p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500/10 text-indigo-600 rounded-xl p-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <p class="text-sm text-gray-500">Selesai</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $completedBookingsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Upcoming Bookings -->
                    <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Trip Mendatang</h3>
                                <p class="text-sm text-gray-500">Pantau jadwal perjalanan yang sudah kamu booking.</p>
                            </div>
                            <span class="inline-flex items-center text-xs font-semibold bg-blue-50 text-blue-600 px-3 py-1 rounded-full">{{ $upcomingTrips->count() }} Jadwal</span>
                        </div>

                        @if($upcomingTrips->count() > 0)
                            <div class="space-y-4">
                                @foreach($upcomingTrips as $booking)
                                    @php
                                        $statusClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                        $contactNumber = preg_replace('/[^0-9]/', '', $booking->customer_phone ?? optional($booking->user)->phone ?? '');
                                        if ($contactNumber && \Illuminate\Support\Str::startsWith($contactNumber, '0')) {
                                            $contactNumber = '62' . substr($contactNumber, 1);
                                        }
                                        $waUrl = $contactNumber ? 'https://wa.me/' . $contactNumber . '?text=' . urlencode('Halo, saya ingin konfirmasi booking #' . $booking->id) : null;
                                    @endphp

                                    <div class="border border-gray-100 rounded-2xl p-4 hover:shadow transition bg-white flex flex-col gap-4">
                                        <div class="flex flex-col gap-1">
                                            <p class="text-sm text-gray-500">{{ $booking->trip->route->origin_city }} ➜ {{ $booking->trip->route->destination_city }}</p>
                                            <p class="text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }} • {{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i') }} WIB</p>
                                            <p class="text-xs text-gray-500">Bus {{ $booking->trip->bus->name ?? '-' }} • {{ $booking->seats_count }} kursi</p>
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                {{ $booking->status_in_indonesian }}
                                            </span>
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if($waUrl)
                                                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-medium text-green-600 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-full">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 2a10 10 0 00-8.94 14.56L2 22l5.61-1.05A10 10 0 1012 2zm0 2a8 8 0 110 16 7.93 7.93 0 01-4.2-1.2l-.3-.19-3 .56.57-2.92-.2-.31A8 8 0 0112 4zm-3.68 4.18c-.19 0-.45.05-.69.38-.24.33-.9.88-.9 2.13s.92 2.47 1.05 2.64c.13.17 1.78 2.84 4.24 3.87 2.1.83 2.53.67 2.98.62.45-.05 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.24-.16-.51-.28-.27-.12-1.58-.78-1.82-.86-.24-.08-.42-.12-.6.12-.18.24-.69.85-.85 1.02-.16.17-.31.19-.58.07-.27-.12-1.15-.42-2.2-1.34-.81-.72-1.36-1.6-1.52-1.87-.16-.27-.02-.41.1-.55.1-.11.24-.28.36-.42.12-.14.16-.24.24-.41.08-.17.04-.31-.02-.43-.06-.12-.54-1.32-.76-1.8-.18-.4-.37-.41-.56-.42z" />
                                                        </svg>
                                                        WhatsApp
                                                    </a>
                                                @endif
                                                <a href="{{ route('user.bookings.show', $booking->id) }}" class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-full">
                                                    Detail
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500">Belum ada trip mendatang. Yuk, pesan perjalananmu!</p>
                                <a href="{{ route('search.form') }}" class="mt-4 inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700">Cari Trip</a>
                            </div>
                        @endif
                    </div>

                    <!-- Riwayat Booking -->
                    <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Riwayat Booking</h3>
                                <p class="text-sm text-gray-500">Lihat ringkasan pesanan terbarumu.</p>
                            </div>
                            <a href="{{ route('user.bookings.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Semua</a>
                        </div>

                        @if($userBookings->count() > 0)
                            <div class="overflow-hidden border border-gray-100 rounded-2xl">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Booking</th>
                                            <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Jadwal</th>
                                            <th scope="col" class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                            <th scope="col" class="px-4 py-3 text-right font-medium text-gray-500">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @foreach($userBookings as $booking)
                                            @php
                                                $statusClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-4 py-4">
                                                    <p class="text-sm font-semibold text-gray-900">#{{ $booking->id }}</p>
                                                    <p class="text-xs text-gray-500">{{ $booking->trip->route->origin_city }} ➜ {{ $booking->trip->route->destination_city }}</p>
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}<br>
                                                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i') }} WIB</span>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                        {{ $booking->status_in_indonesian }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-right">
                                                    <a href="{{ route('user.bookings.show', $booking->id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-sm text-gray-500">Belum ada riwayat booking.</p>
                                <a href="{{ route('search.form') }}" class="mt-3 inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700">Pesan Trip</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>