<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Trip') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Tambah Trip Baru</h1>
                    
                    <form action="{{ route('admin.trips.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="route_id" class="block text-sm font-medium text-gray-700">Rute</label>
                                <select name="route_id" id="route_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('route_id') border-red-500 @enderror" required>
                                    <option value="">Pilih Rute</option>
                                    @foreach($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->origin_city }} - {{ $route->destination_city }}</option>
                                    @endforeach
                                </select>
                                @error('route_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="bus_id" class="block text-sm font-medium text-gray-700">Bus</label>
                                <select name="bus_id" id="bus_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('bus_id') border-red-500 @enderror" required>
                                    <option value="">Pilih Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{ $bus->id }}">{{ $bus->name }} ({{ $bus->plate_number }})</option>
                                    @endforeach
                                </select>
                                @error('bus_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="departure_date" class="block text-sm font-medium text-gray-700">Tanggal Berangkat</label>
                                <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('departure_date') border-red-500 @enderror" required min="{{ date('Y-m-d') }}">
                                @error('departure_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="departure_time" class="block text-sm font-medium text-gray-700">Jam Berangkat</label>
                                <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('departure_time') border-red-500 @enderror" required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga per Kursi</label>
                                <input type="number" step="1000" name="price" id="price" value="{{ old('price') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('price') border-red-500 @enderror" required min="0">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="total_seats" class="block text-sm font-medium text-gray-700">Jumlah Kursi</label>
                                <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('total_seats') border-red-500 @enderror" required min="1">
                                @error('total_seats')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="flex items-center justify-end">
                                <a href="{{ route('admin.trips.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>