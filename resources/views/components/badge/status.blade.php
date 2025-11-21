@props(['status'])

@php
    $statusConfig = [
        'pending' => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-800 border-amber-200'],
        'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
        'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-red-100 text-red-800 border-red-200'],
        'completed' => ['label' => 'Selesai', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'paid' => ['label' => 'Lunas', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
        'sent' => ['label' => 'Terkirim', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
        'failed' => ['label' => 'Gagal', 'class' => 'bg-red-100 text-red-800 border-red-200'],
    ];

    $config = $statusConfig[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800 border-gray-200'];
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $config['class'] }}">
    {{ $config['label'] }}
</span>
