<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Dashboard Header Section -->
            <div class="mb-12 animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                            Halo, <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ Auth::user()->name }}!</span>
                        </h1>
                        <p class="text-lg text-gray-600">Selamat datang di dashboard perjalananmu</p>
                    </div>
                    <!-- Travel Illustration -->
                    <div class="hidden md:block relative">
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-400 rounded-full opacity-80"></div>
                        <div class="absolute -bottom-2 -left-2 w-6 h-6 bg-gradient-to-br from-indigo-300 to-purple-300 rounded-full opacity-60"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <!-- Booking Aktif Card -->
                <div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-sm hover:shadow-neumorphism transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.2s">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/20 to-indigo-400/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Booking Aktif</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $activeBookingsCount }}</p>
                    </div>
                </div>

                <!-- Tiket OK Card -->
                <div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-sm hover:shadow-neumorphism transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-400/20 to-teal-400/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Tiket OK</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $completedBookingsCount }}</p>
                    </div>
                </div>

                <!-- Total Perjalanan Card -->
                <div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-sm hover:shadow-neumorphism transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.4s">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Total Perjalanan</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $totalBookings }}</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Trip Section -->
            <div class="mb-12 animate-fade-in-up" style="animation-delay: 0.5s">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Perjalanan Mendatang</h2>
                
                @if($upcomingTrips->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingTrips as $index => $booking)
                            <div class="group relative overflow-hidden bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-3xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border border-blue-100/50 hover:border-blue-200">
                                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-200/30 to-indigo-200/30 rounded-full -mr-32 -mt-32 group-hover:scale-150 transition-transform duration-700"></div>
                                
                                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                                    <div class="flex items-start gap-6 flex-1">
                                        <!-- Bus Icon -->
                                        <div class="hidden md:flex shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <h3 class="text-2xl font-bold text-gray-900">
                                                    {{ strtoupper($booking->trip->route->origin_city) }} → {{ strtoupper($booking->trip->route->destination_city) }}
                                                </h3>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-4 text-gray-600">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="font-medium">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="font-medium">{{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H.i') }} WIB</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-4">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'confirmed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            ];
                                            $status = $statusConfig[$booking->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }} shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $status['icon'] }}" />
                                            </svg>
                                            {{ strtoupper($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-12 shadow-neumorphism-sm text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada perjalanan mendatang</h3>
                            <p class="text-gray-600 mb-6">Mulai perjalananmu dengan mencari trip yang tersedia</p>
                            <a href="{{ route('search.form') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Trip
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Booking History Section -->
            <div class="animate-fade-in-up" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Riwayat Booking</h2>
                    <a href="{{ route('user.bookings.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold transition-colors duration-200 group">
                        <span>Lihat Semua</span>
                        <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>

                @if($userBookings->count() > 0)
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-neumorphism-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Booking</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jadwal</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-8 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($userBookings as $booking)
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                                'confirmed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800'],
                                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                                'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                            ];
                                            $status = $statusConfig[$booking->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <p class="text-sm font-bold text-gray-900">#{{ $booking->id }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $booking->trip->route->origin_city }} → {{ $booking->trip->route->destination_city }}</p>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i') }} WIB</p>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                                    {{ $booking->status_in_indonesian }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                                <a href="{{ route('user.bookings.show', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition-all duration-200 text-sm">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-12 shadow-neumorphism-sm text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada riwayat booking</h3>
                            <p class="text-gray-600">Booking pertamamu akan muncul di sini</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom Styles untuk Animasi -->
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
            opacity: 0;
        }
    </style>
</x-app-layout>
