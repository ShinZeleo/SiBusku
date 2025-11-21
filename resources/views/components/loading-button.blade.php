@props([
    'type' => 'submit',
    'loadingText' => 'Memproses...',
    'class' => 'px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed',
])

<button
    type="{{ $type }}"
    x-data="{ loading: false }"
    @click="loading = true"
    :disabled="loading"
    :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
    class="{{ $class }}"
>
    <span x-show="!loading">{{ $slot }}</span>
    <span x-show="loading" class="flex items-center gap-2">
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        {{ $loadingText }}
    </span>
</button>

