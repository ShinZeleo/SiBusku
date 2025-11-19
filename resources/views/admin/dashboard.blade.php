<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 mb-10">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Booking</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalBookings }}</p>
                        </div>
                        <div class="rounded-2xl bg-blue-50 p-3 text-blue-600">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Total transaksi booking yang berhasil tercatat.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Trip</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalTrips }}</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-3 text-emerald-600">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Jumlah trip aktif yang siap diberangkatkan.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Bus</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalBuses }}</p>
                        </div>
                        <div class="rounded-2xl bg-amber-50 p-3 text-amber-600">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Armada siap jalan yang terdaftar dalam sistem.</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total Rute</p>
                            <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalRoutes }}</p>
                        </div>
                        <div class="rounded-2xl bg-rose-50 p-3 text-rose-600">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Jaringan rute yang dapat dipasarkan.</p>
                </div>
            </div>

            <!-- Recent Bookings, Upcoming Trips, dan WhatsApp Logs -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Bookings -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Booking Terbaru</h3>
                        @if($recentBookings->count() > 0)
                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-100 text-slate-600 text-xs font-semibold uppercase tracking-wide">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left">Pemesan</th>
                                            <th scope="col" class="px-6 py-3 text-left">Rute</th>
                                            <th scope="col" class="px-6 py-3 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 text-slate-600">
                                        @foreach($recentBookings as $booking)
                                            <tr class="transition-colors hover:bg-slate-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">#{{ $booking->id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $booking->user->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $booking->trip->route->origin_city }} - {{ $booking->trip->route->destination_city }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $booking->status === 'confirmed' ? 'green' : 'yellow' }}-100 text-{{ $booking->status === 'confirmed' ? 'green' : 'yellow' }}-800">
                                                        {{ $booking->status_in_indonesian }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">Tidak ada booking terbaru.</p>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Trips -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Trip Mendatang</h3>
                        @if($upcomingTrips->count() > 0)
                            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-100 text-slate-600 text-xs font-semibold uppercase tracking-wide">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left">Rute</th>
                                            <th scope="col" class="px-6 py-3 text-left">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 text-left">Bus</th>
                                            <th scope="col" class="px-6 py-3 text-left">Kursi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 text-slate-600">
                                        @foreach($upcomingTrips as $trip)
                                            <tr class="transition-colors hover:bg-slate-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $trip->route->origin_city }} - {{ $trip->route->destination_city }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $trip->bus->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $trip->available_seats }}/{{ $trip->total_seats }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">Tidak ada trip mendatang.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent WhatsApp Logs -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Log WhatsApp Terbaru</h3>
                        @if($recentWhatsAppLogs->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentWhatsAppLogs as $log)
                                    <div class="border border-gray-200 rounded p-3">
                                        <div class="flex justify-between">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if($log->booking)
                                                    #{{ $log->booking->id }}
                                                @else
                                                    <span class="text-gray-500">Umum</span>
                                                @endif
                                            </div>
                                            <span class="text-xs px-2 inline-flex leading-5 font-semibold rounded-full
                                                @if($log->status === 'sent') bg-green-100 text-green-800
                                                @elseif($log->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($log->message, 50) }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ $log->created_at->diffForHumans() }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Tidak ada log WhatsApp terbaru.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>