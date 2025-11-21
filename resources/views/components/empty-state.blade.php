@props([
    'icon' => null,
    'title' => 'Tidak ada data',
    'description' => 'Belum ada data yang tersedia.',
    'action' => null,
    'actionLabel' => null,
])

<div class="text-center py-12 px-4">
    @if($icon)
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
            {!! $icon !!}
        </div>
    @else
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
    @endif

    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-500 mb-6">{{ $description }}</p>

    @if($action && $actionLabel)
        <a href="{{ $action }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition">
            {{ $actionLabel }}
        </a>
    @endif
</div>

