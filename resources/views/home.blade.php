<x-app-layout>
    <div class="space-y-12">
        <!-- Hero Section with Search Form -->
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-sky-600 via-sky-700 to-blue-700 px-8 py-12 text-white shadow-xl">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-4xl font-extrabold leading-tight mb-2">SIBUSKU</h1>
                        <p class="text-sky-100/80">Cari dan pesan tiket bus dengan mudah</p>
                    </div>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-white hover:text-sky-700 rounded-lg transition">Register</a>
                        @endauth
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-lg font-semibold mb-2">Masukkan kota asal, tujuan, dan tanggal</p>
                </div>

                <!-- Search Form -->
                <form action="{{ route('search.trips') }}" method="POST" class="bg-white rounded-2xl p-6 shadow-xl">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label for="origin_city" class="block text-sm font-semibold text-gray-700 mb-2">Kota Asal</label>
                            <select
                                name="origin_city"
                                id="origin_city"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                required
                            >
                                <option value="">Pilih Kota Asal</option>
                                @foreach($originCities as $city)
                                    <option value="{{ $city }}" @selected(old('origin_city') === $city)>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="destination_city" class="block text-sm font-semibold text-gray-700 mb-2">Kota Tujuan</label>
                            <select
                                name="destination_city"
                                id="destination_city"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                required
                            >
                                <option value="">Pilih Kota Tujuan</option>
                                @foreach($destinationCities as $city)
                                    <option value="{{ $city }}" @selected(old('destination_city') === $city)>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="departure_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                            <input
                                type="date"
                                name="departure_date"
                                id="departure_date"
                                min="{{ now()->format('Y-m-d') }}"
                                value="{{ old('departure_date', now()->format('Y-m-d')) }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                required
                            >
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full rounded-xl bg-sky-600 hover:bg-sky-700 px-6 py-3 text-white font-semibold shadow-lg transition">
                                CARI TRIP
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Information -->
                <div class="mt-6 text-sm text-sky-100/90">
                    <p class="mb-2">Informasi:</p>
                    <div class="flex flex-col gap-1">
                        <p>▢ Notifikasi akan dikirim lewat WhatsApp</p>
                        <p>▢ Pastikan nomor kamu benar</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search Results Section (if coming from search) -->
        @if(request()->has('search') && isset($trips))
            <section class="space-y-6">
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Hasil Pencarian:</h2>

                    @if(isset($trips) && $trips->count() > 0)
                        <div class="space-y-4">
                            @foreach($trips as $trip)
                                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div class="flex-1">
                                            <p class="text-lg font-bold text-gray-900 mb-1">
                                                {{ strtoupper($trip->route->origin_city) }} → {{ strtoupper($trip->route->destination_city) }}
                                            </p>
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($trip->departure_time)->format('H.i') }}
                                            </p>
                                            <p class="text-sm text-gray-700">
                                                Bus {{ $trip->bus->name }} | {{ $trip->bus->bus_class }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Sisa Kursi: {{ $trip->available_seats }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <p class="text-xl font-bold text-gray-900">Harga: {{ $trip->price_formatted }}</p>
                                            @auth
                                                <button
                                                    onclick="openBookingModal({{ $trip->id }})"
                                                    class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                                                >
                                                    PESAN TIKET
                                                </button>
                                            @else
                                                <a
                                                    href="{{ route('login') }}"
                                                    class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                                                >
                                                    PESAN TIKET
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
                            <p class="text-gray-600">Tidak ditemukan trip untuk rute dan tanggal yang dicari.</p>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <!-- Upcoming Trips -->
        @if($trips->count() > 0)
            <section class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">Rekomendasi Perjalanan</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($trips as $trip)
                        <x-trip-card :trip="$trip" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>