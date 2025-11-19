<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 border-b border-slate-100 pb-4">
                        <h1 class="text-2xl font-bold text-slate-900">Edit Booking #{{ $booking->id }}</h1>
                        <p class="text-sm text-slate-500">Perbarui status dan catatan transaksi pelanggan.</p>
                    </div>

                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700">Status Booking</label>
                                <select name="status" id="status" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('status') border-rose-400 @enderror" required>
                                    <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                    <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="payment_status" class="block text-sm font-semibold text-slate-700">Status Pembayaran</label>
                                <select name="payment_status" id="payment_status" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('payment_status') border-rose-400 @enderror" required>
                                    <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="failed" {{ $booking->payment_status === 'failed' ? 'selected' : '' }}>Gagal</option>
                                    <option value="refunded" {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                                </select>
                                @error('payment_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm font-medium text-slate-500">ID Booking</p>
                                <p class="mt-1 text-lg font-semibold text-slate-900">#{{ $booking->id }}</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm font-medium text-slate-500">Tanggal Booking</p>
                                <p class="mt-1 text-lg font-semibold text-slate-900">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h3 class="text-lg font-semibold text-slate-900">Informasi Pemesan</h3>
                            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Nama</p>
                                    <p class="mt-1 text-sm text-slate-900">{{ $booking->customer_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">No. HP</p>
                                    <p class="mt-1 text-sm text-slate-900">{{ $booking->customer_phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h3 class="text-lg font-semibold text-slate-900">Informasi Trip</h3>
                            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Rute</p>
                                    <p class="mt-1 text-sm text-slate-900">{{ $booking->trip->route->origin_city }} - {{ $booking->trip->route->destination_city }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Tanggal Berangkat</p>
                                    <p class="mt-1 text-sm text-slate-900">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Jam Berangkat</p>
                                    <p class="mt-1 text-sm text-slate-900">{{ $booking->trip->departure_time }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Bus</p>
                                    <p class="mt-1 text-sm text-slate-900">{{ $booking->trip->bus->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                            <h3 class="text-lg font-semibold text-slate-900">Rincian Pembayaran</h3>
                            <div class="mt-3 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Jumlah Kursi</p>
                                    <p class="mt-1 text-sm text-slate-900">{{ $booking->seats_count }} kursi</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-500">Total Harga</p>
                                    <p class="mt-1 text-lg font-bold text-slate-900">{{ $booking->total_price_formatted }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
                            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Update Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>