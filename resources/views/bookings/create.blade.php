<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.1s">
                <a href="{{ route('trips.show', $trip->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-gray-700 hover:text-gray-900 hover:bg-white/50 backdrop-blur-sm border border-gray-200/50 transition-all duration-200 font-medium mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Detail Trip
                </a>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Booking Tiket</h1>
                    <p class="text-lg text-gray-600">Lengkapi data pemesan untuk melanjutkan</p>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Left: Data Pemesan -->
                <div class="lg:col-span-2">
                    <div class="relative overflow-hidden bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-lg border border-white/50 animate-fade-in-up" style="animation-delay: 0.2s">
                        <!-- Watermark Illustration -->
                        <div class="absolute top-0 right-0 w-48 h-48 opacity-5">
                            <svg class="w-full h-full text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        
                        <div class="relative">
                            <h2 class="text-2xl font-bold text-gray-900 mb-8">Data Pemesan</h2>

                            <form
                                id="bookingForm"
                                action="{{ route('bookings.store') }}"
                                method="POST"
                                class="space-y-8"
                                x-data="{ loading: false }"
                                @submit.prevent="loading = true; $el.submit();"
                            >
                                @csrf
                                <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                                <input type="hidden" name="selected_seats" id="selected_seats" value="{{ $selectedSeats }}">

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Nama Lengkap
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="customer_name"
                                            name="customer_name"
                                            value="{{ old('customer_name', auth()->user()->name) }}"
                                            required
                                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5 pl-11 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 {{ $errors->has('customer_name') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/40' : '' }}"
                                            placeholder="Masukkan nama lengkap"
                                        >
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    @error('customer_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor WhatsApp -->
                                <div>
                                    <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Nomor WhatsApp
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="tel"
                                            id="customer_phone"
                                            name="customer_phone"
                                            value="{{ old('customer_phone', auth()->user()->phone) }}"
                                            required
                                            placeholder="08xxxxxxxxxx"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5 pl-11 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 {{ $errors->has('customer_phone') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/40' : '' }}"
                                        >
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Notifikasi akan dikirim ke nomor ini
                                    </p>
                                    @error('customer_phone')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jumlah Kursi -->
                                <div>
                                    <label for="seats_count" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Jumlah Kursi
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <input
                                                type="number"
                                                id="seats_count"
                                                name="seats_count"
                                                min="1"
                                                max="{{ $trip->available_seats }}"
                                                value="{{ old('seats_count', $selectedSeats ? count(explode(',', $selectedSeats)) : 1) }}"
                                                required
                                                data-max-seats="{{ $trip->available_seats }}"
                                                class="w-28 rounded-xl border border-gray-200 bg-white px-4 py-3.5 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 {{ $errors->has('seats_count') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/40' : '' }}"
                                            >
                                        </div>
                                        <button
                                            type="button"
                                            onclick="openSeatModal()"
                                            class="inline-flex items-center gap-2 px-6 py-3.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-xl border border-blue-200 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Pilih Kursi
                                        </button>
                                    </div>
                                    <x-error-message field="seats_count" />
                                    <x-error-message field="selected_seats" />
                                </div>

                                <!-- Info Alert -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 mb-1">Info Penting</p>
                                            <p class="text-sm text-gray-700">Notifikasi booking akan dikirim ke WhatsApp setelah proses selesai. Pastikan nomor WhatsApp Anda aktif.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- CTA Button -->
                                <div class="pt-6 border-t border-gray-100">
                                    <button
                                        type="submit"
                                        :disabled="loading"
                                        class="group w-full px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-full transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 hover:ring-4 hover:ring-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                                    >
                                        <span x-show="!loading" class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Konfirmasi Booking
                                        </span>
                                        <span x-show="loading" class="flex items-center justify-center gap-2">
                                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memproses booking...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right: Ringkasan Perjalanan -->
                <div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-neumorphism-lg border border-white/50 sticky top-24 animate-fade-in-up" style="animation-delay: 0.3s">
                        <h2 class="text-2xl font-bold text-gray-900 mb-8">Ringkasan Perjalanan</h2>

                        <div class="space-y-6">
                            <!-- Rute -->
                            <div class="pb-6 border-b border-gray-100/50">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Rute</p>
                                        <p class="text-xl font-bold text-gray-900">
                                            {{ $trip->route->origin_city }} → {{ $trip->route->destination_city }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal & Waktu -->
                            <div class="pb-6 border-b border-gray-100/50">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Tanggal & Waktu</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }},
                                            {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }} WIB
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bus -->
                            <div class="pb-6 border-b border-gray-100/50">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Bus</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ $trip->bus->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">{{ $trip->bus->bus_class }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Harga per kursi -->
                            <div class="pb-6 border-b border-gray-100/50">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Harga per kursi</p>
                                        <p class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $trip->price_formatted }}</p>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!-- Total -->
                            <div class="pt-4">
                                <div class="flex justify-between items-center mb-3">
                                    <p class="text-lg font-bold text-gray-900">Total</p>
                                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent" id="totalPrice">{{ $trip->price_formatted }}</p>
                                </div>
                                <p class="text-xs text-gray-500 text-center">Harga akan disesuaikan dengan jumlah kursi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seat Selection Modal dengan Animasi -->
    <div
        id="seatModal"
        x-data="{ open: false }"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-md overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 min-h-screen"
        style="display: none;"
    >
        <div
            class="relative w-full max-w-6xl bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 overflow-hidden mx-auto my-auto"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 transform scale-95 translate-y-4"
            @click.away="open = false"
        >
            <!-- Watermark -->
            <div class="absolute top-0 right-0 w-96 h-96 opacity-5 pointer-events-none">
                <svg class="w-full h-full text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>

            <!-- Header -->
            <div class="relative px-8 pt-8 pb-6 border-b border-gray-100/50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900">Pilih Kursi</h3>
                        </div>
                        <p class="text-gray-600 ml-13">Silakan pilih kursi yang tersedia.</p>
                    </div>
                    <button 
                        onclick="closeSeatModal()" 
                        class="w-10 h-10 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100/50 transition-all duration-200 flex items-center justify-center flex-shrink-0"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="relative px-8 py-6">
                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    <!-- Left: Legend & Info -->
                    <div class="w-full lg:w-72 space-y-6 flex-shrink-0">
                        <!-- Legend -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide">Legenda</h4>
                            <div class="flex flex-wrap gap-3">
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-white border border-gray-200 shadow-sm">
                                    <div class="w-8 h-8 rounded-lg border-2 border-gray-300 bg-white flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Tersedia</span>
                                </div>
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-gray-50 border border-gray-200 shadow-sm">
                                    <div class="w-8 h-8 rounded-lg border-2 border-gray-400 bg-gray-400 flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Terisi</span>
                                </div>
                                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-blue-50 border border-blue-200 shadow-sm">
                                    <div class="w-8 h-8 rounded-lg border-2 border-blue-600 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Dipilih</span>
                                </div>
                            </div>
                        </div>

                        <!-- Information Box -->
                        <div class="bg-gradient-to-br from-gray-50 to-blue-50/30 rounded-2xl p-6 border border-gray-200/50 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide">Informasi</h4>
                            <div class="space-y-4">
                                <div class="bg-white/60 rounded-xl p-4 border border-gray-100">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Kursi Dipilih</p>
                                    <p class="text-2xl font-bold text-gray-900">
                                        <span id="modalSelectedCount">0</span>
                                        <span class="text-lg font-normal text-gray-500">/ <span id="modalMaxSeats">{{ $trip->available_seats }}</span></span>
                                    </p>
                                </div>
                                <div class="bg-white/60 rounded-xl p-4 border border-gray-100">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nomor Kursi</p>
                                    <p class="text-lg font-bold text-gray-900" id="modalSelectedSeats">-</p>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Harga</p>
                                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent" id="modalTotalPrice">Rp 0</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Kursi Terbaik Button -->
                        <button
                            type="button"
                            onclick="recommendSeats()"
                            class="w-full px-6 py-3.5 bg-gradient-to-br from-yellow-50 to-amber-50 hover:from-yellow-100 hover:to-amber-100 text-gray-900 font-semibold rounded-full border border-yellow-200 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            Pilih Kursi Terbaik
                        </button>
                    </div>

                    <!-- Seat Map -->
                    <div class="flex-1 flex justify-center items-center min-w-0">
                        <div class="bg-gradient-to-br from-gray-50 via-blue-50/30 to-gray-50 rounded-2xl p-6 shadow-lg border border-gray-200/50 relative overflow-hidden w-full max-h-[600px] overflow-y-auto">
                            <!-- Seat Grid with Pintu Masuk, Driver, Seats, and Pintu Keluar -->
                            <div id="seatMapGrid" class="relative z-10 mx-auto" style="line-height: 0; width: fit-content; transform-origin: center;">
                                <div class="text-center py-12 text-gray-500 text-sm">
                                    Memuat layout kursi...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Glass-style Footer -->
            <div class="relative px-8 py-6 bg-gradient-to-b from-white/80 to-white/60 backdrop-blur-sm border-t border-gray-100/50 flex flex-col sm:flex-row justify-end gap-3">
                <button
                    type="button"
                    onclick="closeSeatModal()"
                    class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-full border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 w-full sm:w-auto"
                >
                    Batal
                </button>
                <button
                    type="button"
                    onclick="confirmSeatSelection()"
                    id="confirmSeatBtn"
                    disabled
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl hover:scale-105 hover:ring-4 hover:ring-blue-500/20 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:ring-0 w-full sm:w-auto flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Gunakan Kursi
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedSeats = [];
        const maxAvailableSeats = {{ $trip->available_seats }}; // Maximum seats available in trip
        let maxSeats = maxAvailableSeats; // Will be updated from seats_count input
        const pricePerSeat = {{ $trip->price }};
        const tripId = {{ $trip->id }};
        const initialSeats = @json($selectedSeats ? explode(',', $selectedSeats) : []);
        let seatLayout = [];
        let bookedSeats = [];

        document.addEventListener('DOMContentLoaded', () => {
            if (initialSeats.length > 0) {
                selectedSeats = initialSeats;
                updateSeatInputs();
            }
            updateTotalPrice();

            // Update maxSeats when seats_count input changes
            const seatsCountInput = document.getElementById('seats_count');
            if (seatsCountInput) {
                const maxFromInput = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;

                seatsCountInput.addEventListener('change', function() {
                    const inputValue = parseInt(this.value);
                    const maxAllowed = parseInt(this.getAttribute('data-max-seats')) || maxAvailableSeats;

                    // Ensure input doesn't exceed max available
                    if (inputValue > maxAllowed) {
                        this.value = maxAllowed;
                        maxSeats = maxAllowed;
                    } else {
                        maxSeats = inputValue || maxAllowed;
                    }

                    updateSeatInfo();
                    // If current selection exceeds new limit, remove excess seats
                    if (selectedSeats.length > maxSeats) {
                        selectedSeats = selectedSeats.slice(0, maxSeats);
                        generateSeatMap();
                        updateSeatInputs();
                        updateSeatInfo();
                        alert('Jumlah kursi yang dipilih melebihi batas. Kursi dipilih disesuaikan.');
                    }
                });
                // Initialize maxSeats from current input value or max available
                maxSeats = parseInt(seatsCountInput.value) || maxFromInput;
            }
        });

        async function openSeatModal() {
            // Update maxSeats from current input value before opening modal
            const seatsCountInput = document.getElementById('seats_count');
            if (seatsCountInput) {
                const inputValue = parseInt(seatsCountInput.value);
                const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;
                maxSeats = inputValue || maxAllowed;
            }

            const modal = document.getElementById('seatModal');
            if (modal && modal.__x) {
                modal.__x.$data.open = true;
            } else {
                modal.style.display = 'block';
            }

            try {
                const response = await fetch(`/api/trips/${tripId}/seats`);
                const data = await response.json();

                seatLayout = data.layout || [];
                bookedSeats = seatLayout.filter(s => s.status === 'booked').map(s => s.seat_number);

                generateSeatMap();
                updateSeatInfo();
            } catch (error) {
                console.error('Error loading seats:', error);
                alert('Gagal memuat data kursi. Silakan refresh halaman.');
            }
        }

        function closeSeatModal() {
            const modal = document.getElementById('seatModal');
            if (modal && modal.__x) {
                modal.__x.$data.open = false;
            } else {
                modal.style.display = 'none';
            }
        }

        function generateSeatMap() {
            const seatMap = document.getElementById('seatMapGrid');
            seatMap.innerHTML = '';

            if (seatLayout.length === 0) {
                seatMap.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada data kursi</div>';
                return;
            }

            // Group seats by row
            const seatsByRow = {};
            seatLayout.forEach(seat => {
                const row = seat.row_index;
                if (!seatsByRow[row]) {
                    seatsByRow[row] = [];
                }
                seatsByRow[row].push(seat);
            });

            // Get sorted row indices
            const sortedRows = Object.keys(seatsByRow).sort((a, b) => parseInt(a) - parseInt(b));
            const maxColIndex = 3; // 0-based, so max is 3 for 4 columns

            // Create Pintu Masuk row (above row A, aligned with column 1 - A1)
            const pintuMasukRow = document.createElement('div');
            pintuMasukRow.className = 'grid items-center';
            pintuMasukRow.style.gridTemplateColumns = '56px 56px 16px 56px 56px 56px';
            pintuMasukRow.style.gap = '4px';
            pintuMasukRow.style.margin = '0';
            pintuMasukRow.style.marginBottom = '4px';
            // Pintu Masuk in column 1 (left side)
            const pintuMasukBtn = document.createElement('div');
            pintuMasukBtn.className = 'w-14 h-8 bg-gradient-to-r from-emerald-500 to-green-600 border border-emerald-600 rounded-xl flex items-center justify-center shadow-md';
            pintuMasukBtn.innerHTML = '<span class="text-[9px] font-bold text-white leading-none flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>MASUK</span>';
            pintuMasukRow.appendChild(pintuMasukBtn);
            // Empty cell for column 2
            const empty1 = document.createElement('div');
            empty1.className = 'w-14 h-8';
            pintuMasukRow.appendChild(empty1);
            // Gang indicator (column 3) - DIPERKECIL
            const gangIndicator1 = document.createElement('div');
            gangIndicator1.className = 'h-8 bg-yellow-100 border border-yellow-400 rounded flex items-center justify-center';
            gangIndicator1.style.width = '16px';
            gangIndicator1.innerHTML = '<span class="text-[9px] font-bold text-yellow-700">↕</span>';
            pintuMasukRow.appendChild(gangIndicator1);
            // Empty cell for column 4
            const empty2 = document.createElement('div');
            empty2.className = 'w-14 h-8';
            pintuMasukRow.appendChild(empty2);
            // Empty cell for column 5 (driver space)
            const empty3 = document.createElement('div');
            empty3.className = 'w-14 h-8';
            pintuMasukRow.appendChild(empty3);
            seatMap.appendChild(pintuMasukRow);

            // Create Driver row (above row A, aligned with column 4 - A4)
            const driverRow = document.createElement('div');
            driverRow.className = 'grid items-center';
            driverRow.style.gridTemplateColumns = '56px 56px 16px 56px 56px 56px';
            driverRow.style.gap = '4px';
            driverRow.style.margin = '0';
            driverRow.style.marginBottom = '4px';
            // Empty cells for columns 1-2
            for (let i = 0; i < 2; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'w-14 h-8';
                driverRow.appendChild(emptyCell);
            }
            // Gang indicator (column 3) - DIPERKECIL
            const gangIndicator2 = document.createElement('div');
            gangIndicator2.className = 'h-8 bg-yellow-100 border border-yellow-400 rounded flex items-center justify-center';
            gangIndicator2.style.width = '16px';
            gangIndicator2.innerHTML = '<span class="text-[9px] font-bold text-yellow-700">↕</span>';
            driverRow.appendChild(gangIndicator2);
            // Empty cell for column 4
            const empty4 = document.createElement('div');
            empty4.className = 'w-14 h-8';
            driverRow.appendChild(empty4);
            // Driver in column 5 (right side)
            const driverBtn = document.createElement('div');
            driverBtn.className = 'w-14 h-8 bg-gradient-to-r from-gray-500 to-gray-600 border border-gray-700 rounded-xl flex items-center justify-center shadow-md';
            driverBtn.innerHTML = '<span class="text-[9px] font-bold text-white leading-none flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>DRIVER</span>';
            driverRow.appendChild(driverBtn);
            seatMap.appendChild(driverRow);

            // Create seat rows (A-H) with 2-2 layout and gang in middle
            sortedRows.forEach(rowIndex => {
                const row = parseInt(rowIndex);
                const rowSeats = seatsByRow[row].sort((a, b) => a.col_index - b.col_index);

                // Create row container: 2 seats left, gang, 2 seats right - GAP MINIMAL
                const rowContainer = document.createElement('div');
                rowContainer.className = 'grid items-stretch';
                rowContainer.style.gridTemplateColumns = '56px 56px 20px 56px 56px 56px';
                rowContainer.style.gap = '6px';
                rowContainer.style.margin = '0';
                rowContainer.style.marginBottom = '4px';
                rowContainer.style.padding = '0';
                rowContainer.style.lineHeight = '0';
                rowContainer.style.fontSize = '0';

                // Left side seats (columns 0-1, which are A1-A2, B1-B2, etc.)
                for (let col = 0; col <= 1; col++) {
                    const seat = rowSeats.find(s => s.col_index === col);

                    if (seat) {
                        const seatBtn = document.createElement('button');
                        seatBtn.type = 'button';
                        seatBtn.className = 'seat-button w-14 h-14 rounded-xl border-2 transition-all duration-300 font-semibold text-xs flex flex-col items-center justify-center relative shadow-sm hover:shadow-lg hover:scale-105';
                        seatBtn.style.margin = '0';
                        seatBtn.style.padding = '0';
                        seatBtn.style.boxSizing = 'border-box';
                        seatBtn.style.lineHeight = '1';
                        seatBtn.style.fontSize = '11px';
                        seatBtn.dataset.seatNumber = seat.seat_number;
                        seatBtn.title = `Kursi ${seat.seat_number}`;
                        
                        // Add seat icon
                        const seatIcon = document.createElement('div');
                        seatIcon.className = 'w-3 h-3 mb-0.5';
                        seatIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                        seatBtn.appendChild(seatIcon);
                        
                        // Add seat number
                        const seatNumber = document.createElement('span');
                        seatNumber.textContent = seat.seat_number;
                        seatBtn.appendChild(seatNumber);
                        
                        seatBtn.onclick = () => toggleSeat(seat.seat_number);

                        if (seat.status === 'booked') {
                            seatBtn.classList.add('bg-gray-300', 'border-gray-400', 'cursor-not-allowed', 'text-gray-600', 'opacity-60');
                            seatBtn.disabled = true;
                            seatIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>';
                        } else {
                            seatBtn.classList.add('bg-white', 'border-gray-300', 'hover:border-blue-500', 'hover:bg-blue-50', 'text-gray-800', 'hover:shadow-md');
                        }

                        if (selectedSeats.includes(seat.seat_number)) {
                            seatBtn.classList.remove('bg-white', 'border-gray-300', 'hover:border-blue-500', 'hover:bg-blue-50', 'text-gray-800', 'hover:shadow-md');
                            seatBtn.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-indigo-600', 'border-blue-600', 'text-white', 'font-bold', 'shadow-md', 'ring-2', 'ring-blue-300');
                            seatBtn.style.animation = 'seatSelected 0.3s ease-out';
                        }

                        rowContainer.appendChild(seatBtn);
                    } else {
                        const emptyCell = document.createElement('div');
                        emptyCell.className = 'w-14 h-14';
                        emptyCell.style.margin = '0';
                        emptyCell.style.padding = '0';
                        emptyCell.style.boxSizing = 'border-box';
                        rowContainer.appendChild(emptyCell);
                    }
                }

                // Gang in middle (column 3) - Improved styling
                const gangCell = document.createElement('div');
                gangCell.className = 'h-14 bg-gradient-to-b from-yellow-50 to-amber-50 border-l-2 border-r-2 border-dashed border-yellow-400 flex items-center justify-center';
                gangCell.style.width = '20px';
                gangCell.style.margin = '0';
                gangCell.style.padding = '0';
                gangCell.style.boxSizing = 'border-box';
                gangCell.innerHTML = '<div class="w-1 h-full bg-gradient-to-b from-yellow-400 to-amber-400 rounded-full"></div>';
                rowContainer.appendChild(gangCell);

                // Right side seats (columns 2-3, which are A3-A4, B3-B4, etc.)
                for (let col = 2; col <= 3; col++) {
                    const seat = rowSeats.find(s => s.col_index === col);

                    if (seat) {
                        const seatBtn = document.createElement('button');
                        seatBtn.type = 'button';
                        seatBtn.className = 'seat-button w-14 h-14 rounded-xl border-2 transition-all duration-300 font-semibold text-xs flex flex-col items-center justify-center relative shadow-sm hover:shadow-lg hover:scale-105';
                        seatBtn.style.margin = '0';
                        seatBtn.style.padding = '0';
                        seatBtn.style.boxSizing = 'border-box';
                        seatBtn.style.lineHeight = '1';
                        seatBtn.style.fontSize = '11px';
                        seatBtn.dataset.seatNumber = seat.seat_number;
                        seatBtn.title = `Kursi ${seat.seat_number}`;
                        
                        // Add seat icon
                        const seatIcon = document.createElement('div');
                        seatIcon.className = 'w-3 h-3 mb-0.5';
                        seatIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                        seatBtn.appendChild(seatIcon);
                        
                        // Add seat number
                        const seatNumber = document.createElement('span');
                        seatNumber.textContent = seat.seat_number;
                        seatBtn.appendChild(seatNumber);
                        
                        seatBtn.onclick = () => toggleSeat(seat.seat_number);

                        if (seat.status === 'booked') {
                            seatBtn.classList.add('bg-gray-300', 'border-gray-400', 'cursor-not-allowed', 'text-gray-600', 'opacity-60');
                            seatBtn.disabled = true;
                            seatIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>';
                        } else {
                            seatBtn.classList.add('bg-white', 'border-gray-300', 'hover:border-blue-500', 'hover:bg-blue-50', 'text-gray-800', 'hover:shadow-md');
                        }

                        if (selectedSeats.includes(seat.seat_number)) {
                            seatBtn.classList.remove('bg-white', 'border-gray-300', 'hover:border-blue-500', 'hover:bg-blue-50', 'text-gray-800', 'hover:shadow-md');
                            seatBtn.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-indigo-600', 'border-blue-600', 'text-white', 'font-bold', 'shadow-md', 'ring-2', 'ring-blue-300');
                            seatBtn.style.animation = 'seatSelected 0.3s ease-out';
                        }

                        rowContainer.appendChild(seatBtn);
                    } else {
                        const emptyCell = document.createElement('div');
                        emptyCell.className = 'w-14 h-14';
                        emptyCell.style.margin = '0';
                        emptyCell.style.padding = '0';
                        emptyCell.style.boxSizing = 'border-box';
                        rowContainer.appendChild(emptyCell);
                    }
                }

                // Empty space for driver alignment (column 5)
                const emptySpace = document.createElement('div');
                emptySpace.className = 'w-14 h-14';
                emptySpace.style.margin = '0';
                emptySpace.style.padding = '0';
                emptySpace.style.boxSizing = 'border-box';
                rowContainer.appendChild(emptySpace);

                seatMap.appendChild(rowContainer);
            });

            // Create Pintu Keluar row (below all seats, aligned with column 1 - H1)
            const pintuKeluarRow = document.createElement('div');
            pintuKeluarRow.className = 'grid items-center';
            pintuKeluarRow.style.gridTemplateColumns = '56px 56px 16px 56px 56px 56px';
            pintuKeluarRow.style.gap = '4px';
            pintuKeluarRow.style.margin = '0';
            pintuKeluarRow.style.marginTop = '4px';
            // Pintu Keluar in column 1 (left side)
            const pintuKeluarBtn = document.createElement('div');
            pintuKeluarBtn.className = 'w-14 h-8 bg-gradient-to-r from-red-500 to-rose-600 border border-red-600 rounded-xl flex items-center justify-center shadow-md';
            pintuKeluarBtn.innerHTML = '<span class="text-[9px] font-bold text-white leading-none flex items-center gap-1">KELUAR<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17l5-5m0 0l-5-5m5 5H6" /></svg></span>';
            pintuKeluarRow.appendChild(pintuKeluarBtn);
            // Empty cell for column 2
            const empty5 = document.createElement('div');
            empty5.className = 'w-14 h-8';
            pintuKeluarRow.appendChild(empty5);
            // Gang indicator (column 3) - DIPERKECIL
            const gangIndicator3 = document.createElement('div');
            gangIndicator3.className = 'h-8 bg-yellow-100 border border-yellow-400 rounded flex items-center justify-center';
            gangIndicator3.style.width = '16px';
            gangIndicator3.innerHTML = '<span class="text-[9px] font-bold text-yellow-700">↕</span>';
            pintuKeluarRow.appendChild(gangIndicator3);
            // Empty cells for columns 4-5
            for (let i = 0; i < 2; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'w-14 h-8';
                pintuKeluarRow.appendChild(emptyCell);
            }
            seatMap.appendChild(pintuKeluarRow);

            // Scale seat map to fit container after rendering
            setTimeout(() => {
                const container = seatMap.parentElement;
                const containerWidth = container.clientWidth - 24; // minus padding
                const containerHeight = container.clientHeight - 24; // minus padding

                const seatMapWidth = seatMap.scrollWidth;
                const seatMapHeight = seatMap.scrollHeight;

                if (seatMapWidth > 0 && seatMapHeight > 0) {
                    const scaleX = containerWidth / seatMapWidth;
                    const scaleY = containerHeight / seatMapHeight;
                    const scale = Math.min(scaleX, scaleY, 1); // Don't scale up, only down

                    seatMap.style.transform = `scale(${scale})`;
                    seatMap.style.transformOrigin = 'top center';
                }
            }, 50);
        }

        async function recommendSeats() {
            const seatsCountInput = document.getElementById('seats_count');
            const seatsCount = parseInt(seatsCountInput.value || '1', 10);
            const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;

            // Update maxSeats from input, but don't exceed max available
            maxSeats = Math.min(seatsCount, maxAllowed);

            try {
                const response = await fetch(`/api/trips/${tripId}/seats/recommend?count=${seatsCount}`);
                const data = await response.json();

                if (data.recommended_seats && data.recommended_seats.length > 0) {
                    selectedSeats = [];
                    data.recommended_seats.forEach(seatNumber => {
                        if (!bookedSeats.includes(seatNumber) && selectedSeats.length < maxSeats) {
                            selectedSeats.push(seatNumber);
                        }
                    });

                    generateSeatMap();
                    updateSeatInfo();
                    seatsCountInput.value = selectedSeats.length;
                } else {
                    alert('Tidak ada kursi yang direkomendasikan. Silakan pilih manual.');
                }
            } catch (error) {
                console.error('Error getting recommendations:', error);
                alert('Gagal mendapatkan rekomendasi kursi.');
            }
        }

        function toggleSeat(seatNumber) {
            if (bookedSeats.includes(seatNumber)) return;

            const index = selectedSeats.indexOf(seatNumber);
            if (index > -1) {
                selectedSeats.splice(index, 1);
            } else {
                // Update maxSeats from input before checking
                const seatsCountInput = document.getElementById('seats_count');
                if (seatsCountInput) {
                    const inputValue = parseInt(seatsCountInput.value);
                    const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;
                    maxSeats = inputValue || maxAllowed;
                }

                if (selectedSeats.length < maxSeats) {
                    selectedSeats.push(seatNumber);
                } else {
                    alert('Maksimal ' + maxSeats + ' kursi yang dapat dipilih. Silakan ubah jumlah kursi di form jika ingin memilih lebih banyak.');
                    return;
                }
            }
            generateSeatMap();
            updateSeatInfo();
        }

        function updateSeatInfo() {
            // Update maxSeats from input
            const seatsCountInput = document.getElementById('seats_count');
            if (seatsCountInput) {
                const inputValue = parseInt(seatsCountInput.value);
                const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;
                maxSeats = inputValue || maxAllowed;
            }

            document.getElementById('modalSelectedCount').textContent = selectedSeats.length;
            document.getElementById('modalMaxSeats').textContent = maxSeats;
            document.getElementById('modalSelectedSeats').textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '-';

            const total = selectedSeats.length * pricePerSeat;
            document.getElementById('modalTotalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');

            const confirmBtn = document.getElementById('confirmSeatBtn');
            confirmBtn.disabled = selectedSeats.length === 0;
        }

        function confirmSeatSelection() {
            if (selectedSeats.length === 0) {
                alert('Pilih minimal 1 kursi');
                return;
            }

            updateSeatInputs();
            updateTotalPrice();
            closeSeatModal();
        }

        function updateSeatInputs() {
            document.getElementById('selected_seats').value = selectedSeats.join(',');
            document.getElementById('seats_count').value = selectedSeats.length;
        }

        function updateTotalPrice() {
            const seatsCount = parseInt(document.getElementById('seats_count').value || '0', 10);
            const total = seatsCount * pricePerSeat;
            document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('seats_count').addEventListener('input', updateTotalPrice);
    </script>

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

        @keyframes seatSelected {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .seat-button {
            position: relative;
        }

        .seat-button:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            white-space: nowrap;
            margin-bottom: 4px;
            pointer-events: none;
            z-index: 10;
        }

        .seat-button:hover::before {
            content: '';
            position: absolute;
            bottom: calc(100% - 4px);
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.8);
            pointer-events: none;
            z-index: 10;
        }
    </style>
</x-app-layout>
