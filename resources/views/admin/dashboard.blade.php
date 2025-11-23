<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Admin Dashboard Header -->
            <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex items-center gap-3 mb-3">
                    <h1 class="text-4xl font-bold text-gray-900">ADMIN DASHBOARD</h1>
                    <div class="h-1 w-24 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full"></div>
                </div>
                <p class="text-gray-600 text-lg">Overview aktivitas booking dan trip hari ini.</p>
            </div>

            <!-- Statistic Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <!-- Trip Aktif -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-neumorphism-lg border border-white/50 hover:scale-105 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></div>
                    </div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Trip Aktif</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $totalTrips }}</p>
                    <p class="text-xs text-gray-500 mt-2">Total trip tersedia</p>
                </div>

                <!-- Booking Hari Ini -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-neumorphism-lg border border-white/50 hover:scale-105 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    </div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Booking Hari Ini</p>
                    <p class="text-4xl font-bold text-gray-900">{{ \App\Models\Booking::whereDate('created_at', today())->count() }}</p>
                    <p class="text-xs text-gray-500 mt-2">Booking baru hari ini</p>
                </div>

                <!-- Pending -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-neumorphism-lg border border-white/50 hover:scale-105 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.4s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="h-2 w-2 rounded-full bg-yellow-500 animate-pulse"></div>
                    </div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Pending</p>
                    <p class="text-4xl font-bold text-gray-900">{{ \App\Models\Booking::where('status', 'pending')->count() }}</p>
                    <p class="text-xs text-gray-500 mt-2">Menunggu konfirmasi</p>
                </div>

                <!-- Penumpang Bulan Ini -->
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-neumorphism-lg border border-white/50 hover:scale-105 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.5s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="h-2 w-2 rounded-full bg-purple-500 animate-pulse"></div>
                    </div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Penumpang Bulan Ini</p>
                    <p class="text-4xl font-bold text-gray-900">{{ \App\Models\Booking::whereMonth('created_at', now()->month)->sum('seats_count') }}</p>
                    <p class="text-xs text-gray-500 mt-2">Total penumpang bulan ini</p>
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                <a href="{{ route('admin.trips.index') }}" class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-neumorphism-sm border border-white/50 hover:scale-105 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 group-hover:bg-blue-200 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Kelola Trip</p>
                            <p class="text-xs text-gray-500">Manage trips</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.buses.index') }}" class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-neumorphism-sm border border-white/50 hover:scale-105 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 group-hover:bg-indigo-200 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">Kelola Bus</p>
                            <p class="text-xs text-gray-500">Manage buses</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.routes.index') }}" class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-neumorphism-sm border border-white/50 hover:scale-105 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 group-hover:bg-purple-200 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Kelola Rute</p>
                            <p class="text-xs text-gray-500">Manage routes</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.whatsapp-logs.index') }}" class="bg-white/80 backdrop-blur-sm rounded-2xl p-5 shadow-neumorphism-sm border border-white/50 hover:scale-105 transition-all duration-300 group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 group-hover:bg-emerald-200 transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">Log WhatsApp</p>
                            <p class="text-xs text-gray-500">View WA logs</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Booking Terbaru Section -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-lg border border-white/50 animate-fade-in-up" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Booking Terbaru</h2>
                        <p class="text-sm text-gray-600">5 booking terbaru yang perlu perhatian</p>
                    </div>
                    <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 text-sm font-semibold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition-all duration-200">
                        Lihat Semua
                    </a>
                </div>

                @if($recentBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama User</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rute</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal & Jam</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100/50">
                                @foreach($recentBookings as $index => $booking)
                                    <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                                    <span class="text-sm font-bold text-blue-600">{{ substr($booking->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $booking->user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ $booking->trip->route->origin_city }} → {{ $booking->trip->route->destination_city }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'],
                                                    'confirmed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'],
                                                    'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>'],
                                                    'completed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'],
                                                ];
                                                $status = $statusConfig[$booking->status] ?? $statusConfig['pending'];
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }} border {{ $status['border'] }}">
                                                {!! $status['icon'] !!}
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                @if($booking->status === 'pending')
                                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="px-4 py-2 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full transition-all duration-200">
                                                        Konfirmasi
                                                    </a>
                                                @endif
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-full transition-all duration-200">
                                                    Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 font-medium">Tidak ada booking terbaru.</p>
                        <p class="text-sm text-gray-500 mt-1">Semua booking sudah ditangani.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
            opacity: 0;
        }

        .shadow-neumorphism-lg {
            box-shadow: 12px 12px 24px rgba(0, 0, 0, 0.08), -12px -12px 24px rgba(255, 255, 255, 0.8);
        }

        .shadow-neumorphism-sm {
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.06), -4px -4px 8px rgba(255, 255, 255, 0.8);
        }
    </style>
</x-app-layout>
