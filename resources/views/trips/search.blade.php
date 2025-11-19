<x-app-layout>
    <div class="space-y-8">
        <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-lg">
            <div class="mb-8 space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Cari jadwal perjalanan</p>
                <h1 class="text-3xl font-bold text-slate-900">Lengkapi data perjalanan kamu</h1>
                <p class="text-sm text-slate-500">Pilih kota keberangkatan dan tujuan, lalu tentukan tanggal yang diinginkan. Sistem akan menampilkan trip yang masih memiliki kursi tersedia.</p>
            </div>

            <form action="{{ route('search.trips') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid gap-6 md:grid-cols-3">
                    <div>
                        <label for="origin_city" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Keberangkatan</label>
                        <div class="mt-2 rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus-within:border-sky-500 focus-within:ring-2 focus-within:ring-sky-500/40">
                            <select name="origin_city" id="origin_city" class="w-full bg-transparent text-sm text-slate-900 focus:outline-none" required>
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
                            <select name="destination_city" id="destination_city" class="w-full bg-transparent text-sm text-slate-900 focus:outline-none" required>
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

                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
                        <span class="text-slate-400">Kota favorit:</span>
                        @foreach($destinationCities->take(4) as $city)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">{{ $city }}</span>
                        @endforeach
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-10 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/30 transition hover:bg-sky-700">
                        Cari Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
