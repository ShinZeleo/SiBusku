@props(['title', 'value', 'icon' => null, 'trend' => null, 'color' => 'sky'])

<div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $value }}</p>
            @if($trend)
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-sm {{ $trend['positive'] ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $trend['positive'] ? '↑' : '↓' }} {{ $trend['value'] }}
                    </span>
                    <span class="text-sm text-gray-500">{{ $trend['label'] }}</span>
                </div>
            @endif
        </div>
        @if($icon)
            <div class="flex-shrink-0">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-{{ $color }}-100">
                    {!! $icon !!}
                </div>
            </div>
        @endif
    </div>
</div>

