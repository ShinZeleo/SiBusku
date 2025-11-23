<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-gray-700 hover:text-gray-900 hover:bg-white/50 backdrop-blur-sm border border-gray-200/50 transition-all duration-200 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </a>
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900">Detail Trip</h1>
                            <p class="text-gray-600 mt-1">{{ $trip->route->origin_city }} → {{ $trip->route->destination_city }} • {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Route Information Card -->
                    <div class="relative overflow-hidden bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-lg border border-white/50 animate-fade-in-up" style="animation-delay: 0.2s">
                        <!-- Watermark Illustration -->
                        <div class="absolute top-0 right-0 w-64 h-64 opacity-5">
                            <svg class="w-full h-full text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        
                        <div class="relative flex items-center justify-between">
                            <div class="flex-1">
                                <h2 class="text-4xl font-bold text-gray-900 mb-3">
                                    {{ $trip->route->origin_city }}
                                    <span class="text-blue-500 mx-3">→</span>
                                    {{ $trip->route->destination_city }}
                                </h2>
                                <p class="text-gray-600">Rute perjalanan dengan durasi estimasi {{ $trip->route->duration_estimate }} jam</p>
                            </div>
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold {{ $trip->status === 'scheduled' ? 'bg-emerald-100 text-emerald-800' : ($trip->status === 'available' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                @if($trip->status === 'scheduled')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                                {{ $trip->status === 'scheduled' ? 'Scheduled' : ($trip->status === 'available' ? 'Available' : 'Closed') }}
                            </span>
                        </div>
                    </div>

                    <!-- Trip Schedule Section -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.3s">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Jadwal Perjalanan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Tanggal Berangkat -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-2xl p-6 border border-gray-100">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal Berangkat</p>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">{{ $trip->departure_date_formatted }}</p>
                            </div>

                            <!-- Jam Berangkat -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-2xl p-6 border border-gray-100">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Jam Berangkat</p>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }} WIB</p>
                            </div>

                            <!-- Durasi Perjalanan -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-2xl p-6 border border-gray-100">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Durasi Perjalanan</p>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">{{ $trip->route->duration_estimate }} jam</p>
                            </div>

                            <!-- Sisa Kursi -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-2xl p-6 border border-gray-100">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Sisa Kursi</p>
                                </div>
                                <p class="text-2xl font-bold text-gray-900">{{ $trip->available_seats }} / {{ $trip->total_seats }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bus Information Section -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-lg border border-white/50 animate-fade-in-up" style="animation-delay: 0.4s">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Informasi Bus</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-gray-600 font-medium">Nama Bus</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $trip->bus->name }}</span>
                            </div>
                            <div class="flex items-center justify-between py-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <span class="text-gray-600 font-medium">Kelas Bus</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $trip->bus->bus_class }}</span>
                            </div>
                            <div class="flex items-center justify-between py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-gray-600 font-medium">Plat Nomor</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $trip->bus->plate_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Price & Booking Panel -->
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 rounded-3xl p-8 shadow-neumorphism-lg text-white animate-fade-in-up" style="animation-delay: 0.5s">
                        <div class="text-center mb-8">
                            <p class="text-sm uppercase tracking-wide text-blue-100 mb-3">Harga per Kursi</p>
                            <p class="text-5xl font-bold mb-2">{{ $trip->price_formatted }}</p>
                            <p class="text-sm text-blue-100">per penumpang</p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-white/20">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-blue-100 text-sm">Total Kursi</span>
                                    <span class="font-bold text-lg">{{ $trip->total_seats }}</span>
                                </div>
                                <div class="h-px bg-white/20"></div>
                                <div class="flex items-center justify-between">
                                    <span class="text-blue-100 text-sm">Kursi Tersedia</span>
                                    <span class="font-bold text-lg text-emerald-300">{{ $trip->available_seats }}</span>
                                </div>
                                <div class="h-px bg-white/20"></div>
                                <div class="flex items-center justify-between">
                                    <span class="text-blue-100 text-sm">Kursi Terisi</span>
                                    <span class="font-bold text-lg">{{ $trip->total_seats - $trip->available_seats }}</span>
                                </div>
                            </div>
                        </div>

                        @auth
                            @if($trip->status === 'scheduled' && $trip->available_seats > 0)
                                <a
                                    href="{{ route('bookings.create', ['trip_id' => $trip->id]) }}"
                                    class="group block w-full px-6 py-4 bg-white text-blue-700 font-semibold rounded-full transition-all duration-200 shadow-xl hover:shadow-2xl hover:scale-105 hover:ring-4 hover:ring-white/30 text-center"
                                >
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Pilih Kursi dan Booking
                                    </span>
                                </a>
                            @else
                                <button
                                    disabled
                                    class="w-full px-6 py-4 bg-gray-400 text-white font-semibold rounded-full cursor-not-allowed opacity-60"
                                >
                                    {{ $trip->available_seats === 0 ? 'Kursi Habis' : 'Trip Tidak Tersedia' }}
                                </button>
                            @endif
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="group block w-full px-6 py-4 bg-white text-blue-700 font-semibold rounded-full transition-all duration-200 shadow-xl hover:shadow-2xl hover:scale-105 hover:ring-4 hover:ring-white/30 text-center"
                            >
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Login untuk Booking
                                </span>
                            </a>
                        @endauth
                    </div>

                    <!-- Support Box -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-neumorphism-sm border border-white/50 animate-fade-in-up" style="animation-delay: 0.6s">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900 mb-2">Butuh Bantuan?</h4>
                                <p class="text-sm text-gray-600 mb-4">Hubungi admin untuk bantuan atau konfirmasi.</p>
                                <a
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('services.fonnte.admin_phone', '62895802990864')) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    Chat Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
