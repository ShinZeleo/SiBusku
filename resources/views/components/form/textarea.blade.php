@props(['name', 'label', 'required' => false, 'value' => '', 'placeholder' => '', 'rows' => 3, 'class' => ''])

<div>
    @if(isset($label))
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 @error($name) border-red-400 @enderror {{ $class }}"
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>

    <x-error-message :field="$name" />
</div>

