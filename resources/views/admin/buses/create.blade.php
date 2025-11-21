<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">FORM BUS</h1>

                    <form action="{{ route('admin.buses.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Bus</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="plate_number" class="block text-sm font-semibold text-gray-700 mb-2">Plat Nomor</label>
                            <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40" required>
                            @error('plate_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bus_class" class="block text-sm font-semibold text-gray-700 mb-2">Kelas</label>
                            <select name="bus_class" id="bus_class" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40" required>
                                <option value="">Pilih Kelas</option>
                                <option value="Eksekutif" {{ old('bus_class') === 'Eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                                <option value="AC" {{ old('bus_class') === 'AC' ? 'selected' : '' }}>AC</option>
                                <option value="Ekonomi" {{ old('bus_class') === 'Ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                            </select>
                            @error('bus_class')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas</label>
                            <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40" required min="1">
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="is_active" id="is_active" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40" required>
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition">
                                SIMPAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>