<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900">Data Booking</h1>
                            <p class="text-sm text-slate-500">Monitor transaksi pemesanan terbaru.</p>
                        </div>
                        <a href="{{ route('admin.bookings.export.csv') }}" class="inline-flex items-center rounded-xl border border-emerald-500 px-4 py-2 text-sm font-semibold text-emerald-600 transition hover:bg-emerald-50">
                            Export ke CSV
                        </a>
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

                    @if($bookings->count() > 0)
                        <div class="overflow-x-auto rounded-2xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-100 text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Booking</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rute</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kursi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notif WA</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 text-slate-600">
                                    @foreach($bookings as $booking)
                                        <tr class="transition-colors hover:bg-slate-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">#{{ $booking->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $booking->trip->route->origin_city }} - {{ $booking->trip->route->destination_city }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->seats_count }} kursi</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $booking->total_price_formatted }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $booking->status === 'confirmed' ? 'green' : ($booking->status === 'pending' ? 'yellow' : 'red') }}-100 text-{{ $booking->status === 'confirmed' ? 'green' : ($booking->status === 'pending' ? 'yellow' : 'red') }}-800">
                                                    {{ $booking->status_in_indonesian }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $waLog = $booking->latestWhatsappLog;
                                                    $waStatusClass = 'bg-gray-100 text-gray-800';
                                                    $waStatusLabel = 'Belum ada log';

                                                    if ($waLog) {
                                                        $map = [
                                                            'sent' => ['label' => 'Sukses', 'class' => 'bg-emerald-100 text-emerald-800'],
                                                            'pending' => ['label' => 'Menunggu', 'class' => 'bg-yellow-100 text-yellow-800'],
                                                            'failed' => ['label' => 'Gagal', 'class' => 'bg-red-100 text-red-800'],
                                                        ];

                                                        $waStatusLabel = $map[$waLog->status]['label'] ?? strtoupper($waLog->status);
                                                        $waStatusClass = $map[$waLog->status]['class'] ?? 'bg-gray-100 text-gray-800';
                                                    }
                                                @endphp
                                                <div>
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $waStatusClass }}">
                                                        {{ $waStatusLabel }}
                                                    </span>
                                                    @if($waLog)
                                                        <p class="text-xs text-gray-500 mt-1">{{ $waLog->sent_at ? $waLog->sent_at->format('d M Y H:i') : 'Belum dikirim' }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">Edit</a>
                                                <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking ini?')">
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
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-lg text-gray-500">Belum ada data booking.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>