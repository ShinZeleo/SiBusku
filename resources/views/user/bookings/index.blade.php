<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen">
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">Riwayat Booking Saya</h1>
                        <p class="text-lg text-gray-600 mt-1">Lihat semua riwayat perjalanan dan status booking Anda</p>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="mb-8 animate-fade-in-up" style="animation-delay: 0.2s">
                <form method="GET" action="{{ route('user.bookings.index') }}" class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-neumorphism-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cari Booking</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="ID Booking atau Rute" class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-200 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                            <div class="relative">
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-200 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                            <div class="relative">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-3 pl-11 rounded-xl border border-gray-200 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Terapkan Filter
                        </button>
                        @if(request()->hasAny(['status', 'search', 'date_from', 'date_to']))
                            <a href="{{ route('user.bookings.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Booking History Table -->
            <div class="animate-fade-in-up" style="animation-delay: 0.3s">
                @if($bookings->count() > 0)
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-neumorphism-sm overflow-hidden">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID Booking</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rute</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Berangkat</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kursi</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Harga</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">WA</th>
                                        <th class="px-8 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($bookings as $booking)
                                        @php
                                            $waBadge = booking_whatsapp_badge($booking);
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'confirmed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            ];
                                            $status = $statusConfig[$booking->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <span class="text-sm font-bold text-gray-900">#{{ $booking->id }}</span>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->trip->route->origin_city }}</span>
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                    </svg>
                                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->trip->route->destination_city }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}</p>
                                                        <p class="text-xs text-gray-500">{{ $booking->trip->departure_time }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-900">{{ $booking->seats_count }} kursi</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <span class="text-sm font-bold text-gray-900">{{ $booking->total_price_formatted }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $status['icon'] }}" />
                                                    </svg>
                                                    {{ $booking->status_in_indonesian }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold {{ $waBadge['classes'] }}">
                                                    <span class="h-2 w-2 rounded-full {{ $waBadge['dot'] }}"></span>
                                                    {{ $waBadge['label'] }}
                                                </span>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-3">
                                                    <a href="{{ route('user.bookings.show', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition-all duration-200 text-sm">
                                                        Detail
                                                    </a>
                                                    @if($booking->status === 'pending')
                                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')" class="inline">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg transition-all duration-200 text-sm">
                                                                Batal
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($bookings->hasPages())
                            <div class="px-8 py-6 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        Menampilkan <span class="font-semibold">{{ $bookings->firstItem() }}</span> sampai <span class="font-semibold">{{ $bookings->lastItem() }}</span> dari <span class="font-semibold">{{ $bookings->total() }}</span> booking
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{ $bookings->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-16 shadow-neumorphism-sm text-center animate-fade-in-up">
                        <div class="max-w-md mx-auto">
                            <div class="w-32 h-32 mx-auto mb-8 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum ada riwayat booking</h3>
                            <p class="text-gray-600 mb-8">Mulai perjalananmu dengan mencari dan memesan trip yang tersedia</p>
                            <a href="{{ route('search.form') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari dan Pesan Trip
                            </a>
                        </div>
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

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to right, #3b82f6, #6366f1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to right, #2563eb, #4f46e5);
        }

        /* Pagination Styles */
        nav[role="navigation"] a,
        nav[role="navigation"] span {
            border-radius: 0.75rem !important;
            transition: all 0.2s !important;
        }

        nav[role="navigation"] a:hover {
            background: #f3f4f6 !important;
        }

        nav[role="navigation"] .relative.inline-flex.items-center.px-4 {
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
        }

        nav[role="navigation"] .relative.inline-flex.items-center.px-4:hover {
            background: #f3f4f6 !important;
        }

        nav[role="navigation"] .relative.inline-flex.items-center.px-4[aria-current="page"] {
            background: linear-gradient(to right, #3b82f6, #6366f1) !important;
            color: white !important;
            border: none !important;
        }
    </style>
</x-app-layout>
