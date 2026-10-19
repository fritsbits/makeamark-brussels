@props(['name', 'label', 'type' => 'text', 'rows' => null, 'required' => false])
@php
    $previous = old($name, '');
    $value = is_scalar($previous) ? (string) $previous : '';
@endphp
<flux:field class="application-form__field">
    <label for="{{ $name }}"><flux:label id="{{ $name }}-label">{{ $label }}@if ($required) <span class="application-form__required-marker" aria-hidden="true">*</span>@endif</flux:label></label>
    @if ($type === 'select')
        <flux:select variant="default" :id="$name" :name="$name" :required="$required" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $name }}-error" {{ $attributes }}>{{ $slot }}</flux:select>
    @elseif ($rows !== null)
        <flux:textarea :id="$name" :name="$name" :rows="$rows" :required="$required" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $name }}-error" {{ $attributes }}>{{ $value }}</flux:textarea>
    @else
        <flux:input :id="$name" :name="$name" :type="$type" :value="$value" :required="$required" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $name }}-error" {{ $attributes }} />
    @endif
    <flux:error :name="$name" id="{{ $name }}-error" :icon="false" />
</flux:field>
