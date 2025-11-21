<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">ADMIN DASHBOARD</h1>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2">Trip Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalTrips }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2">Booking Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Booking::whereDate('created_at', today())->count() }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2">Pending</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Booking::where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-sm text-gray-600 mb-2">Penumpang Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Booking::whereMonth('created_at', now()->month)->sum('seats_count') }}</p>
                </div>
            </div>

            <!-- Booking Terbaru -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Booking Terbaru</h2>

                @if($recentBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentBookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $booking->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $booking->trip->route->origin_city }} → {{ $booking->trip->route->destination_city }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H.i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800',
                                                    'completed' => 'bg-blue-100 text-blue-800',
                                                ];
                                                $statusClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                {{ strtoupper($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($booking->status === 'pending')
                                                <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-sky-600 hover:text-sky-900">Konfirmasi</a>
                                            @else
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="text-gray-600 hover:text-gray-900">Detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">Tidak ada booking terbaru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>