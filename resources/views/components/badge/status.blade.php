@props(['status'])

@php
    $statusConfig = [
        'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
        'confirmed' => ['label' => 'Confirmed', 'class' => 'bg-green-100 text-green-800'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-800'],
        'completed' => ['label' => 'Completed', 'class' => 'bg-blue-100 text-blue-800'],
        'scheduled' => ['label' => 'Scheduled', 'class' => 'bg-emerald-100 text-emerald-800'],
        'running' => ['label' => 'Running', 'class' => 'bg-amber-100 text-amber-800'],
    ];

    $config = $statusConfig[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
@endphp

<span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $config['class'] }}">
    {{ $config['label'] }}
</span>

