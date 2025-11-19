<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log WhatsApp') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Total Log</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Berhasil Terkirim</p>
                        <p class="text-3xl font-bold text-emerald-600">{{ number_format($stats['sent']) }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm text-gray-500">Gagal Terkirim</p>
                        <p class="text-3xl font-bold text-red-600">{{ number_format($stats['failed']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Riwayat Log WhatsApp</h1>
                            <p class="text-sm text-gray-500">Pantau pesan notifikasi yang dikirim melalui Fonnte.</p>
                        </div>
                        <span class="inline-flex items-center text-sm text-gray-500">
                            Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} log
                        </span>
                    </div>

                    @if($logs->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($logs as $log)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="font-medium text-gray-900">{{ optional($log->sent_at ?? $log->created_at)->format('d M Y, H:i') }}</div>
                                                <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="font-medium">{{ $log->phone }}</div>
                                                @if($log->booking && ($log->booking->customer_name || optional($log->booking->user)->name))
                                                    <div class="text-xs text-gray-500">{{ $log->booking->customer_name ?? optional($log->booking->user)->name }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-600">
                                                {{ \Illuminate\Support\Str::limit($log->message, 90) }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($log->booking)
                                                    <div class="font-semibold">#{{ $log->booking->id }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ optional($log->booking->trip->route)->origin_city }} → {{ optional($log->booking->trip->route)->destination_city }}
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-500">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @php($isSent = $log->status === 'sent')
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $isSent ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                    {{ $isSent ? 'Berhasil' : 'Gagal' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-lg text-gray-500">Belum ada log WhatsApp.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
