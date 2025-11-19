<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 border-b border-slate-100 pb-4">
                        <h1 class="text-2xl font-bold text-slate-900">Edit Bus</h1>
                        <p class="text-sm text-slate-500">Perbarui informasi bus sesuai kebutuhan operasional.</p>
                    </div>

                    <form action="{{ route('admin.buses.update', $bus->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Bus</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $bus->name) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('name') border-rose-400 @enderror" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="plate_number" class="block text-sm font-semibold text-slate-700">Nomor Plat</label>
                            <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number', $bus->plate_number) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('plate_number') border-rose-400 @enderror" required>
                            @error('plate_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-slate-700">Kapasitas (jumlah kursi)</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $bus->capacity) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('capacity') border-rose-400 @enderror" required min="1">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bus_class" class="block text-sm font-semibold text-slate-700">Kelas Bus</label>
                            <input type="text" name="bus_class" id="bus_class" value="{{ old('bus_class', $bus->bus_class) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('bus_class') border-rose-400 @enderror" required>
                            @error('bus_class')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="flex items-center text-sm font-medium text-slate-700">
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_active', $bus->is_active) ? 'checked' : '' }}>
                                <span class="ml-2">Bus Aktif</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.buses.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
                            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>