<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Rute') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Edit Rute</h1>
                    
                    <form action="{{ route('admin.routes.update', $route->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <label for="origin_city" class="block text-sm font-medium text-gray-700">Kota Asal</label>
                                <input type="text" name="origin_city" id="origin_city" value="{{ old('origin_city', $route->origin_city) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('origin_city') border-red-500 @enderror" required>
                                @error('origin_city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="destination_city" class="block text-sm font-medium text-gray-700">Kota Tujuan</label>
                                <input type="text" name="destination_city" id="destination_city" value="{{ old('destination_city', $route->destination_city) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('destination_city') border-red-500 @enderror" required>
                                @error('destination_city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="duration_estimate" class="block text-sm font-medium text-gray-700">Perkiraan Durasi (jam)</label>
                                <input type="number" step="0.01" name="duration_estimate" id="duration_estimate" value="{{ old('duration_estimate', $route->duration_estimate) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('duration_estimate') border-red-500 @enderror" required min="0.1">
                                @error('duration_estimate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="is_active" class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_active', $route->is_active) ? 'checked' : '' }}>
                                    <span class="ml-2 block text-sm text-gray-900">Rute Aktif</span>
                                </label>
                            </div>
                            
                            <div class="flex items-center justify-end">
                                <a href="{{ route('admin.routes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>