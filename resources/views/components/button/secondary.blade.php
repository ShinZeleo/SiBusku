@props(['type' => 'button', 'class' => ''])

<button
    type="{{ $type }}"
    class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed {{ $class }}"
    {{ $attributes }}
>
    {{ $slot }}
</button>

