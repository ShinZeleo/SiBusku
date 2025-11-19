<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Bus') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">Data Bus</h1>
                            <p class="text-sm text-slate-500">Kelola seluruh armada bus yang terdaftar.</p>
                        </div>
                        <a href="{{ route('admin.buses.create') }}" class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Tambah Bus</a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($buses->count() > 0)
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-100 text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left">Nama Bus</th>
                                        <th scope="col" class="px-6 py-3 text-left">Nomor Plat</th>
                                        <th scope="col" class="px-6 py-3 text-left">Kapasitas</th>
                                        <th scope="col" class="px-6 py-3 text-left">Kelas</th>
                                        <th scope="col" class="px-6 py-3 text-left">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 text-slate-600">
                                    @foreach($buses as $bus)
                                        <tr class="transition-colors hover:bg-slate-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $bus->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $bus->plate_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $bus->capacity }} kursi</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $bus->bus_class }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $bus->is_active ? 'green' : 'red' }}-100 text-{{ $bus->is_active ? 'green' : 'red' }}-800">
                                                    {{ $bus->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.buses.edit', $bus->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">Edit</a>
                                                <form action="{{ route('admin.buses.destroy', $bus->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bus ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-semibold text-red-600 hover:text-red-800">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $buses->links() }}
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-lg text-gray-500">Belum ada data bus.</p>
                            <a href="{{ route('admin.buses.create') }}" class="mt-4 inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Tambah Bus</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>