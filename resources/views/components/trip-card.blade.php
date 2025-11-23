@props(['trip', 'showLink' => true])

<div class="group relative overflow-hidden bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-neumorphism-sm hover:shadow-neumorphism transition-all duration-300 border border-gray-100/50 h-full flex flex-col">
    <!-- Gradient Accent -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-200/20 to-indigo-200/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
    
    <div class="relative flex flex-col flex-1">
        <!-- Header with Route -->
        <div class="mb-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Rute Perjalanan</p>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                        {{ $trip->route->origin_city }}
                        <span class="text-blue-500 mx-2">→</span>
                        {{ $trip->route->destination_city }}
                    </h3>
                    <p class="text-sm text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Durasi estimasi {{ $trip->route->duration_estimate }} jam
                    </p>
                </div>
            </div>
        </div>

        <!-- Trip Details Grid -->
        <div class="grid gap-3 sm:grid-cols-3 mb-6">
            <div class="rounded-xl bg-gradient-to-br from-gray-50 to-gray-100/50 p-4 border border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal</p>
                </div>
                <p class="font-bold text-gray-900">{{ $trip->departure_date_formatted }}</p>
            </div>
            <div class="rounded-xl bg-gradient-to-br from-gray-50 to-gray-100/50 p-4 border border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Waktu</p>
                </div>
                <p class="font-bold text-gray-900">{{ $trip->departure_time }}</p>
            </div>
            <div class="rounded-xl bg-gradient-to-br from-gray-50 to-gray-100/50 p-4 border border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Bus</p>
                </div>
                <p class="font-bold text-gray-900 text-sm">{{ $trip->bus->name }}</p>
                <p class="text-xs text-gray-600">{{ $trip->bus->bus_class }}</p>
            </div>
        </div>

        <!-- Price & Availability -->
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100 mt-auto">
            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">Mulai dari</p>
                <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    {{ $trip->price_formatted }}
                </p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold text-emerald-800 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ $trip->available_seats }} kursi tersedia
                </span>
            </div>
        </div>

        <!-- CTA Button -->
        @if($showLink && $trip->status === 'scheduled')
            <a
                href="{{ route('trips.show', $trip->id) }}"
                class="block w-full px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl text-center group"
            >
                <span class="flex items-center justify-center gap-2">
                    Lihat Detail & Booking
                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </a>
        @endif
    </div>
</div>
