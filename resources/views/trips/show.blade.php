<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sky-600 hover:text-sky-700 font-semibold mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Detail Trip</h1>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Route Card -->
                    <x-card>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Rute Perjalanan</p>
                                <h2 class="text-2xl font-bold text-gray-900">
                                    {{ $trip->route->origin_city }}
                                    <span class="text-sky-600">→</span>
                                    {{ $trip->route->destination_city }}
                                </h2>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $trip->status === 'scheduled' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($trip->status) }}
                            </span>
                        </div>
                    </x-card>

                    <!-- Schedule Info -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Jadwal Perjalanan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Tanggal Berangkat</p>
                                <p class="text-lg font-bold text-gray-900">{{ $trip->departure_date_formatted }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Jam Berangkat</p>
                                <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }} WIB</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Durasi Perjalanan</p>
                                <p class="text-lg font-bold text-gray-900">{{ $trip->route->duration_estimate }} jam</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs font-semibold uppercase text-gray-500 mb-1">Sisa Kursi</p>
                                <p class="text-lg font-bold text-gray-900">{{ $trip->available_seats }} dari {{ $trip->total_seats }}</p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Bus Info -->
                    <x-card>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Bus</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Nama Bus</span>
                                <span class="font-semibold text-gray-900">{{ $trip->bus->name }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                <span class="text-gray-600">Kelas Bus</span>
                                <span class="font-semibold text-gray-900">{{ $trip->bus->bus_class }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-gray-600">Plat Nomor</span>
                                <span class="font-semibold text-gray-900">{{ $trip->bus->plate_number }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Price Card -->
                    <x-card class="bg-gradient-to-br from-sky-600 to-blue-700 text-white">
                        <div class="text-center">
                            <p class="text-sm uppercase tracking-wide text-sky-100 mb-2">Harga per Kursi</p>
                            <p class="text-4xl font-bold mb-1">{{ $trip->price_formatted }}</p>
                            <p class="text-sm text-sky-100 mb-6">per penumpang</p>

                            <div class="border-t border-sky-500/30 pt-4 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-sky-100">Total Kursi</span>
                                    <span class="font-semibold">{{ $trip->total_seats }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sky-100">Kursi Tersedia</span>
                                    <span class="font-semibold">{{ $trip->available_seats }}</span>
                                </div>
                            </div>

                            @auth
                                @if($trip->status === 'scheduled' && $trip->available_seats > 0)
                                    <a
                                        href="{{ route('bookings.create', ['trip_id' => $trip->id]) }}"
                                        class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-white text-sky-600 px-6 py-3 font-semibold shadow-lg transition hover:bg-sky-50"
                                    >
                                        Pilih Kursi dan Booking
                                    </a>
                                @else
                                    <button
                                        disabled
                                        class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-gray-400 text-white px-6 py-3 font-semibold cursor-not-allowed"
                                    >
                                        {{ $trip->available_seats === 0 ? 'Kursi Habis' : 'Trip Tidak Tersedia' }}
                                    </button>
                                @endif
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-white text-sky-600 px-6 py-3 font-semibold shadow-lg transition hover:bg-sky-50"
                                >
                                    Login untuk Booking
                                </a>
                            @endauth
                        </div>
                    </x-card>

                    <!-- Help Card -->
                    <x-card class="bg-emerald-50 border-emerald-200">
                        <h4 class="text-lg font-semibold text-emerald-900 mb-2">Butuh Bantuan?</h4>
                        <p class="text-sm text-emerald-800 mb-4">Hubungi admin melalui WhatsApp untuk konfirmasi atau pertanyaan lainnya.</p>
                        <a
                            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('services.fonnte.admin_phone', '62895802990864')) }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Chat Admin
                        </a>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
