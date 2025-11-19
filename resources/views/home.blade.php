<x-app-layout>
    <div class="space-y-12">
        <!-- Hero -->
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-sky-600 via-sky-700 to-blue-700 px-8 py-12 text-white shadow-xl">
            <div class="grid gap-10 md:grid-cols-2 md:items-center">
                <div class="space-y-6">
                    <p class="text-sm uppercase tracking-[0.3em] text-sky-100">Pesan tiket bus mudah</p>
                    <div>
                        <h1 class="text-4xl font-extrabold leading-tight">SIBUSKU, Teman Terbaik Perjalanan Antar Kota</h1>
                        <p class="mt-4 text-base text-sky-100/80">Bandingkan jadwal, pilih kursi favorit ala Tiketux, dan dapatkan notifikasi WhatsApp otomatis setelah pembayaran.</p>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm font-semibold text-slate-900">
                        <div class="rounded-2xl bg-white/90 px-5 py-3">
                            <p class="text-2xl font-bold">120+</p>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Rute aktif</p>
                        </div>
                        <div class="rounded-2xl bg-white/90 px-5 py-3">
                            <p class="text-2xl font-bold">4.8/5</p>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Rating pengguna</p>
                        </div>
                        <div class="rounded-2xl bg-white/90 px-5 py-3">
                            <p class="text-2xl font-bold">24/7</p>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Bantuan WA</p>
                        </div>
                    </div>
                </div>
                <div class="relative hidden md:block">
                    <div class="absolute -top-10 right-6 h-36 w-36 rounded-full bg-sky-400/40 blur-3xl"></div>
                    <div class="rounded-3xl border border-white/20 bg-white/10 p-6 backdrop-blur">
                        <p class="text-sm uppercase tracking-[0.4em] text-sky-100">Live seat picker</p>
                        <div class="mt-6 space-y-4">
                            <div class="flex items-center justify-between rounded-2xl bg-white/20 px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-slate-100">Pilih kursi interaktif</p>
                                    <p class="text-xs text-sky-100/80">Lihat ketersediaan real-time</p>
                                </div>
                                <span class="rounded-full bg-white/80 px-3 py-1 text-xs font-semibold text-slate-900">Baru</span>
                            </div>
                            <div class="rounded-2xl bg-white/20 px-4 py-3">
                                <p class="text-sm font-medium text-slate-100">Notifikasi otomatis</p>
                                <p class="text-xs text-sky-100/80">WA terkirim setelah booking</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search Form -->
        <section class="rounded-3xl border border-slate-100 bg-white p-8 shadow-lg">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Cari jadwal perjalanan</p>
                    <h2 class="text-2xl font-bold text-slate-900">Temukan bus terbaik hanya dengan tiga langkah</h2>
                    <p class="text-sm text-slate-500">Pilih kota, tanggal, lalu lanjutkan booking. Semua bisa dilakukan tanpa reload halaman yang berat.</p>
                </div>
                <a href="{{ route('search.form') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-sky-600 hover:text-sky-600">
                    Lihat semua jadwal
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <form action="{{ route('search.trips') }}" method="POST" class="mt-8">
                @csrf
                <div class="grid gap-6 md:grid-cols-3">
                    <div>
                        <label for="origin_city" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Keberangkatan</label>
                        <div class="mt-2 rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus-within:border-sky-500 focus-within:ring-2 focus-within:ring-sky-500/40">
                            <select
                                name="origin_city"
                                id="origin_city"
                                class="w-full bg-transparent text-sm text-slate-900 focus:outline-none"
                                required
                            >
                                <option value="">Pilih Kota Asal</option>
                                @foreach($originCities as $city)
                                    <option value="{{ $city }}" @selected(old('origin_city') === $city)>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="destination_city" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tujuan</label>
                        <div class="mt-2 rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus-within:border-sky-500 focus-within:ring-2 focus-within:ring-sky-500/40">
                            <select
                                name="destination_city"
                                id="destination_city"
                                class="w-full bg-transparent text-sm text-slate-900 focus:outline-none"
                                required
                            >
                                <option value="">Pilih Kota Tujuan</option>
                                @foreach($destinationCities as $city)
                                    <option value="{{ $city }}" @selected(old('destination_city') === $city)>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="departure_date" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal berangkat</label>
                        <div class="mt-2 rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus-within:border-sky-500 focus-within:ring-2 focus-within:ring-sky-500/40">
                            <input
                                type="date"
                                name="departure_date"
                                id="departure_date"
                                min="{{ now()->format('Y-m-d') }}"
                                value="{{ old('departure_date', now()->format('Y-m-d')) }}"
                                class="w-full border-0 bg-transparent text-sm text-slate-900 focus:outline-none"
                                required
                            >
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
                        <span class="text-slate-400">Rute populer:</span>
                        @foreach($destinationCities->take(4) as $city)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">{{ $city }}</span>
                        @endforeach
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-10 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/30 transition hover:bg-sky-700">
                        Cari Jadwal
                    </button>
                </div>
            </form>
        </section>

        <!-- Upcoming Trips -->
        <section class="space-y-6">
            <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Trip mendatang</p>
                    <h2 class="text-2xl font-bold text-slate-900">Rekomendasi perjalanan populer minggu ini</h2>
                </div>
                <a href="{{ route('search.form') }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700">Telusuri semua &rarr;</a>
            </div>

            @if($trips->count() > 0)
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($trips as $trip)
                        <x-trip-card :trip="$trip" />
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-12 text-center shadow-sm">
                    <p class="text-base font-semibold text-slate-700">Belum ada trip yang siap dipesan.</p>
                    <p class="mt-2 text-sm text-slate-500">Tambahkan data bus dan trip melalui dashboard admin untuk menampilkan rekomendasi di sini.</p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
