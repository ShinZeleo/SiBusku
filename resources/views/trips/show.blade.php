<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Trip - {{ $trip->route->origin_city }} ke {{ $trip->route->destination_city }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                <x-trip-card :trip="$trip" />

                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="rounded-2xl bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">Jadwal Perjalanan</h3>
                            <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm text-gray-600">
                                <div class="rounded-xl border border-gray-100 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tanggal Berangkat</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $trip->departure_date_formatted }}</dd>
                                </div>
                                <div class="rounded-xl border border-gray-100 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Jam Berangkat</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $trip->departure_time }}</dd>
                                </div>
                                <div class="rounded-xl border border-gray-100 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Durasi Estimasi</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $trip->route->duration_estimate }} jam</dd>
                                </div>
                                <div class="rounded-xl border border-gray-100 p-4">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status Trip</dt>
                                    <dd class="mt-1 inline-flex items-center rounded-full bg-{{ $trip->status === 'scheduled' ? 'emerald' : ($trip->status === 'running' ? 'amber' : 'gray') }}-100 px-3 py-1 text-xs font-semibold text-{{ $trip->status === 'scheduled' ? 'emerald' : ($trip->status === 'running' ? 'amber' : 'gray') }}-800">
                                        {{ ucfirst($trip->status) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-2xl bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Bus</h3>
                            <dl class="mt-4 space-y-3 text-sm text-gray-600">
                                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">
                                    <dt class="text-gray-500">Nama Bus</dt>
                                    <dd class="font-semibold text-gray-900">{{ $trip->bus->name }}</dd>
                                </div>
                                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">
                                    <dt class="text-gray-500">Kelas</dt>
                                    <dd class="font-semibold text-gray-900">{{ $trip->bus->bus_class }}</dd>
                                </div>
                                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">
                                    <dt class="text-gray-500">Plat Nomor</dt>
                                    <dd class="font-semibold text-gray-900">{{ $trip->bus->plate_number }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-sm">
                            <p class="text-sm uppercase tracking-wide text-slate-400">Ringkasan Harga</p>
                            <p class="mt-2 text-3xl font-bold">{{ $trip->price_formatted }}</p>
                            <p class="text-sm text-slate-300">per kursi</p>
                            <div class="mt-4 flex items-center justify-between border-t border-slate-800 pt-4 text-sm text-slate-200">
                                <span>Total Kursi</span>
                                <span class="font-semibold">{{ $trip->total_seats }}</span>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-sm text-slate-200">
                                <span>Kursi Tersedia</span>
                                <span class="font-semibold">{{ $trip->available_seats }}</span>
                            </div>
                            <a href="{{ route('search.form') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-white/10 px-4 py-2 text-center text-sm font-semibold text-white transition hover:bg-white/20">Cari Trip Lain</a>
                        </div>

                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900">
                            <h4 class="text-lg font-semibold">Butuh Bantuan?</h4>
                            <p class="mt-2 text-sm">Hubungi admin melalui WhatsApp untuk konfirmasi ketersediaan kursi atau pertanyaan lainnya.</p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('services.fonnte.admin_phone', '62895802990864')) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 16v3l3-3h11V5H5v11zm-2 5V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5l-2 2z" />
                                </svg>
                                Chat Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
