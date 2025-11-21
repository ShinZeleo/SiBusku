@props(['class' => '', 'padding' => 'p-6'])

<div class="bg-white border border-gray-200 rounded-2xl {{ $padding }} shadow-sm hover:shadow-md transition-shadow duration-200 {{ $class }}">
    {{ $slot }}
</div>
