<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Edit Booking #{{ $booking->id }}</h1>
                    
                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status Booking</label>
                                <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-500 @enderror" required>
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
                                <label for="payment_status" class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                                <select name="payment_status" id="payment_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('payment_status') border-red-500 @enderror" required>
                                    <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="failed" {{ $booking->payment_status === 'failed' ? 'selected' : '' }}>Gagal</option>
                                    <option value="refunded" {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}>Dikembalikan</option>
                                </select>
                                @error('payment_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">ID Booking</p>
                                    <p class="mt-1 text-sm text-gray-900">#{{ $booking->id }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tanggal Booking</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-3">Informasi Pemesan</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Nama</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->customer_name }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">No. HP</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->customer_phone }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-3">Informasi Trip</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Rute</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->trip->route->origin_city }} - {{ $booking->trip->route->destination_city }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Tanggal Berangkat</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Jam Berangkat</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->trip->departure_time }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Bus</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->trip->bus->name }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold mb-3">Rincian Pembayaran</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Jumlah Kursi</p>
                                        <p class="mt-1 text-sm text-gray-900">{{ $booking->seats_count }} kursi</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Total Harga</p>
                                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $booking->total_price_formatted }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-end">
                                <a href="{{ route('admin.bookings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Booking</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>