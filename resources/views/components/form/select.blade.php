@props(['name', 'label', 'required' => false, 'options' => [], 'value' => '', 'class' => ''])

<div>
    @if(isset($label))
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 @error($name) border-red-400 @enderror {{ $class }}"
        {{ $attributes }}
    >
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    <x-error-message :field="$name" />
</div>

