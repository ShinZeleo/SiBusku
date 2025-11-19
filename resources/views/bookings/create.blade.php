<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pesan Trip - {{ $trip->route->origin_city }} ke {{ $trip->route->destination_city }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="rounded-2xl bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Data Pemesan</h3>
                        <p class="mt-1 text-sm text-gray-500">Masukkan data lengkap untuk melanjutkan proses booking.</p>

                        <form action="{{ route('bookings.store') }}" method="POST" class="mt-6 space-y-5">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" required class="mt-2 block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                                <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone_number) }}" required class="mt-2 block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="08xxxxxxxxxx">
                                @error('customer_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="seats_count" class="block text-sm font-medium text-gray-700">Jumlah Kursi</label>
                                <input type="number" id="seats_count" name="seats_count" min="1" max="{{ $trip->available_seats }}" value="{{ old('seats_count', 1) }}" required class="mt-2 block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('seats_count')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Maksimal {{ $trip->available_seats }} kursi tersedia.</p>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">Konfirmasi &amp; Pesan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-sm">
                        <p class="text-sm uppercase tracking-wide text-slate-400">Ringkasan Trip</p>
                        <div class="mt-4 space-y-3 text-sm text-slate-200">
                            <div class="flex items-center justify-between">
                                <span>Rute</span>
                                <span class="font-semibold text-right">{{ $trip->route->origin_city }} &rarr; {{ $trip->route->destination_city }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Tanggal</span>
                                <span class="font-semibold">{{ $trip->departure_date_formatted }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Jam</span>
                                <span class="font-semibold">{{ $trip->departure_time }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Harga per Kursi</span>
                                <span class="font-semibold">{{ $trip->price_formatted }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Kursi Tersedia</span>
                                <span class="font-semibold">{{ $trip->available_seats }}</span>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-slate-800 pt-4">
                            <div class="flex items-center justify-between text-base font-semibold">
                                <span>Estimasi Total</span>
                                <span id="booking-total">{{ $trip->price_formatted }}</span>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">Total akan disesuaikan dengan jumlah kursi.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6 text-emerald-900">
                        <h4 class="text-base font-semibold">Info Konfirmasi WhatsApp</h4>
                        <p class="mt-2 text-sm">Setelah mengirim permintaan booking, tim kami akan menghubungi Anda melalui WhatsApp untuk konfirmasi dan pembayaran.</p>
                        <div class="mt-4 rounded-xl bg-white/70 p-4 text-sm text-emerald-900">
                            <p class="font-semibold">Nomor Admin</p>
                            <p class="text-lg font-bold">{{ config('services.fonnte.admin_phone', '62895802990864') }}</p>
                            <p class="text-xs text-emerald-700">Pastikan nomor WhatsApp Anda aktif dan dapat dihubungi.</p>
                        </div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('services.fonnte.admin_phone', '62895802990864')) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Chat via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const seatInput = document.getElementById('seats_count');
            const totalLabel = document.getElementById('booking-total');
            const pricePerSeat = {{ $trip->price }};
            const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

            const updateTotal = () => {
                const seats = parseInt(seatInput.value || '0', 10);
                totalLabel.textContent = formatter.format(seats * pricePerSeat);
            };

            seatInput.addEventListener('input', updateTotal);
            updateTotal();
        });
    </script>
</x-app-layout>
