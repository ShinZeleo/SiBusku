<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cari Jadwal Perjalanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Cari Jadwal Perjalanan</h1>
                    
                    <form action="{{ route('search.trips') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
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
                            
                            <div class="flex items-center justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Cari Jadwal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>