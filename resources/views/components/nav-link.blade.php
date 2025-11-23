@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold leading-5 text-blue-700 bg-blue-50/80 backdrop-blur-sm border border-blue-200/50 focus:outline-none transition-all duration-200 ease-in-out'
            : 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium leading-5 text-gray-600 hover:text-gray-900 hover:bg-white/50 backdrop-blur-sm border border-transparent hover:border-gray-200/50 focus:outline-none transition-all duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
