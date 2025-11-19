<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Rute') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 border-b border-slate-100 pb-4">
                        <h1 class="text-2xl font-bold text-slate-900">Edit Rute</h1>
                        <p class="text-sm text-slate-500">Sesuaikan detail rute agar informasi selalu akurat.</p>
                    </div>

                    <form action="{{ route('admin.routes.update', $route->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="origin_city" class="block text-sm font-semibold text-slate-700">Kota Asal</label>
                            <input type="text" name="origin_city" id="origin_city" value="{{ old('origin_city', $route->origin_city) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('origin_city') border-rose-400 @enderror" required>
                            @error('origin_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="destination_city" class="block text-sm font-semibold text-slate-700">Kota Tujuan</label>
                            <input type="text" name="destination_city" id="destination_city" value="{{ old('destination_city', $route->destination_city) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('destination_city') border-rose-400 @enderror" required>
                            @error('destination_city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration_estimate" class="block text-sm font-semibold text-slate-700">Perkiraan Durasi (jam)</label>
                            <input type="number" step="0.01" name="duration_estimate" id="duration_estimate" value="{{ old('duration_estimate', $route->duration_estimate) }}" class="mt-2 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 @error('duration_estimate') border-rose-400 @enderror" required min="0.1">
                            @error('duration_estimate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="flex items-center text-sm font-medium text-slate-700">
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_active', $route->is_active) ? 'checked' : '' }}>
                                <span class="ml-2">Rute Aktif</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.routes.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
                            <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>