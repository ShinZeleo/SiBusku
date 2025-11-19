<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Trip') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 border-b border-slate-100 pb-4">
                        <h1 class="text-2xl font-bold text-slate-900">Edit Trip</h1>
                        <p class="text-sm text-slate-500">Perbarui jadwal dan detail perjalanan yang sudah ada.</p>
                    </div>

                    <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="route_id" class="block text-sm font-semibold text-slate-700">Rute</label>
                            <select name="route_id" id="route_id" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('route_id') border-rose-400 @enderror" required>
                                <option value="">Pilih Rute</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}" @selected(old('route_id', $trip->route_id) == $route->id)>{{ $route->origin_city }} - {{ $route->destination_city }}</option>
                                @endforeach
                            </select>
                            @error('route_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bus_id" class="block text-sm font-semibold text-slate-700">Bus</label>
                            <select name="bus_id" id="bus_id" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('bus_id') border-rose-400 @enderror" required>
                                <option value="">Pilih Bus</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}" @selected(old('bus_id', $trip->bus_id) == $bus->id)>{{ $bus->name }} ({{ $bus->plate_number }})</option>
                                @endforeach
                            </select>
                            @error('bus_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="departure_date" class="block text-sm font-semibold text-slate-700">Tanggal Berangkat</label>
                            <input type="date" name="departure_date" id="departure_date" value="{{ old('departure_date', $trip->departure_date) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('departure_date') border-rose-400 @enderror" required>
                            @error('departure_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="departure_time" class="block text-sm font-semibold text-slate-700">Jam Berangkat</label>
                            <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time', $trip->departure_time) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('departure_time') border-rose-400 @enderror" required>
                            @error('departure_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="price" class="block text-sm font-semibold text-slate-700">Harga per Kursi</label>
                                <input type="number" step="1000" name="price" id="price" value="{{ old('price', $trip->price) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('price') border-rose-400 @enderror" required min="0">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="total_seats" class="block text-sm font-semibold text-slate-700">Jumlah Kursi</label>
                                <input type="number" name="total_seats" id="total_seats" value="{{ old('total_seats', $trip->total_seats) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('total_seats') border-rose-400 @enderror" required min="1">
                                @error('total_seats')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.trips.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
                            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
