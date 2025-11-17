<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Detail Booking #{{ $booking->id }}</h1>
                            <p class="text-gray-600">Dibooking pada {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }}</p>
                        </div>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $booking->status === 'confirmed' ? 'green' : ($booking->status === 'pending' ? 'yellow' : 'red') }}-100 text-{{ $booking->status === 'confirmed' ? 'green' : ($booking->status === 'pending' ? 'yellow' : 'red') }}-800">
                            {{ $booking->status_in_indonesian }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informasi Trip -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-lg font-semibold mb-4">Informasi Trip</h2>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Rute</p>
                                    <p class="font-medium">{{ $booking->trip->route->origin_city }} - {{ $booking->trip->route->destination_city }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Berangkat</p>
                                    <p class="font-medium">{{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jam Berangkat</p>
                                    <p class="font-medium">{{ $booking->trip->departure_time }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Durasi Perjalanan</p>
                                    <p class="font-medium">{{ $booking->trip->route->duration_estimate }} jam</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Nama Bus</p>
                                    <p class="font-medium">{{ $booking->trip->bus->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kelas Bus</p>
                                    <p class="font-medium">{{ $booking->trip->bus->bus_class }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Booking -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h2 class="text-lg font-semibold mb-4">Informasi Booking</h2>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Nama Pemesan</p>
                                    <p class="font-medium">{{ $booking->customer_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">No. HP</p>
                                    <p class="font-medium">{{ $booking->customer_phone }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jumlah Kursi</p>
                                    <p class="font-medium">{{ $booking->seats_count }} kursi</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Harga per Kursi</p>
                                    <p class="font-medium">{{ $booking->trip->price_formatted }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Harga</p>
                                    <p class="font-bold text-lg text-blue-600">{{ $booking->total_price_formatted }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status Pembayaran</p>
                                    <p class="font-medium">{{ $booking->payment_status === 'paid' ? 'Lunas' : ($booking->payment_status === 'pending' ? 'Menunggu Pembayaran' : 'Gagal') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Booking -->
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold mb-4">Status Booking</h2>
                        <div class="flex items-center">
                            <div class="flex-1 text-center">
                                <div class="w-10 h-10 rounded-full bg-{{ $booking->status !== 'cancelled' ? 'blue' : 'gray' }}-500 flex items-center justify-center mx-auto">
                                    <span class="text-white text-sm">1</span>
                                </div>
                                <div class="mt-2 text-sm font-medium {{ $booking->status !== 'cancelled' ? 'text-blue-600' : 'text-gray-500' }}">Dipesan</div>
                            </div>
                            <div class="flex-1 text-center">
                                <div class="w-10 h-10 rounded-full bg-{{ in_array($booking->status, ['confirmed', 'completed']) ? 'blue' : ($booking->status === 'pending' ? 'gray' : 'gray') }}-500 flex items-center justify-center mx-auto">
                                    <span class="text-white text-sm">2</span>
                                </div>
                                <div class="mt-2 text-sm font-medium {{ in_array($booking->status, ['confirmed', 'completed']) ? 'text-blue-600' : 'text-gray-500' }}">Dikonfirmasi</div>
                            </div>
                            <div class="flex-1 text-center">
                                <div class="w-10 h-10 rounded-full bg-{{ $booking->status === 'completed' ? 'blue' : 'gray' }}-500 flex items-center justify-center mx-auto">
                                    <span class="text-white text-sm">3</span>
                                </div>
                                <div class="mt-2 text-sm font-medium {{ $booking->status === 'completed' ? 'text-blue-600' : 'text-gray-500' }}">Selesai</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.bookings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Kembali ke Daftar</a>
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit Booking</a>
                        @else
                            <a href="{{ route('user.bookings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Kembali ke Riwayat</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>