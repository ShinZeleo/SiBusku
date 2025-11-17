<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold text-center mb-8">Selamat Datang di SIBUSKU</h1>
                    <p class="text-lg text-center mb-10">Sistem Booking Tiket Bus Antar Kota</p>
                    
                    <!-- Search Form -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-10">
                        <h2 class="text-xl font-semibold mb-4">Cari Jadwal Perjalanan</h2>
                        <form action="{{ route('search.trips') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="origin" class="block text-sm font-medium text-gray-700">Keberangkatan</label>
                                    <select name="origin" id="origin" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="">Pilih Kota Asal</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}">{{ $route->origin_city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="destination" class="block text-sm font-medium text-gray-700">Tujuan</label>
                                    <select name="destination" id="destination" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="">Pilih Kota Tujuan</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}">{{ $route->destination_city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="departure_date" class="block text-sm font-medium text-gray-700">Tanggal Berangkat</label>
                                    <input type="date" name="departure_date" id="departure_date" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required min="{{ date('Y-m-d') }}">
                                </div>
                                
                                <div class="flex items-end">
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Recent Trips -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Trip Mendatang</h2>
                        @if($trips->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($trips as $trip)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">{{ $trip->route->origin_city }} - {{ $trip->route->destination_city }}</h3>
                                                <p class="text-sm text-gray-500">{{ $trip->route->duration_estimate }} jam</p>
                                            </div>
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                                Rp {{ number_format($trip->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600"><strong>Bus:</strong> {{ $trip->bus->name }}</p>
                                            <p class="text-sm text-gray-600"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }}</p>
                                            <p class="text-sm text-gray-600"><strong>Jam:</strong> {{ $trip->departure_time }}</p>
                                            <p class="text-sm text-gray-600"><strong>Kursi Tersedia:</strong> {{ $trip->available_seats }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500">Tidak ada trip mendatang saat ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>