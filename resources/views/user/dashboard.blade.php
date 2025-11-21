<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">USER DASHBOARD</h1>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2">Booking Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeBookingsCount }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2">Tiket OK</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $completedBookingsCount }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2">Total Perjalanan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalBookings }}</p>
                </div>
            </div>

            <!-- Perjalanan Mendatang -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Perjalanan Mendatang</h2>

                @if($upcomingTrips->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingTrips as $booking)
                            <div class="border border-gray-200 rounded-xl p-4">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="text-lg font-bold text-gray-900 mb-1">
                                            {{ strtoupper($booking->trip->route->origin_city) }} → {{ strtoupper($booking->trip->route->destination_city) }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H.i') }}
                                        </p>
                                    </div>
                                    <div>
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'completed' => 'bg-blue-100 text-blue-800',
                                            ];
                                            $statusClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $statusClass }}">
                                            {{ strtoupper($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">Belum ada perjalanan mendatang.</p>
                        <a href="{{ route('search.form') }}" class="mt-4 inline-block px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition">
                            Cari Trip
                        </a>
                    </div>
                @endif
            </div>

            <!-- Riwayat Booking -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Riwayat Booking</h2>
                    <a href="{{ route('user.bookings.index') }}" class="text-sky-600 hover:text-sky-700 font-semibold">Lihat Semua →</a>
                </div>

                @if($userBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($userBookings as $booking)
                                    @php
                                        $statusClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-semibold text-gray-900">#{{ $booking->id }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->trip->route->origin_city }} → {{ $booking->trip->route->destination_city }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}<br>
                                            <span class="text-xs">{{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i') }} WIB</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                {{ $booking->status_in_indonesian }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('user.bookings.show', $booking->id) }}" class="text-sky-600 hover:text-sky-900">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">Belum ada riwayat booking.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>