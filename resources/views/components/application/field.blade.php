@props(['name', 'label', 'type' => 'text', 'rows' => null, 'required' => false, 'hint' => null])
@php
    $previous = old($name, '');
    $value = is_scalar($previous) ? (string) $previous : '';
    $describedBy = ($hint ? $name.'-hint ' : '').$name.'-error';
@endphp
<flux:field class="application-form__field">
    <label for="{{ $name }}"><flux:label id="{{ $name }}-label">{{ $label }}@if ($required) <span class="application-form__required-marker" aria-hidden="true">*</span>@endif</flux:label></label>
    @if ($hint)<flux:text id="{{ $name }}-hint" class="application-form__hint">{{ $hint }}</flux:text>@endif
    @if ($type === 'select')
        <flux:select variant="default" :id="$name" :name="$name" :required="$required" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $describedBy }}" {{ $attributes }}>{{ $slot }}</flux:select>
    @elseif ($rows !== null)
        <flux:textarea :id="$name" :name="$name" :rows="$rows" :required="$required" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $describedBy }}" {{ $attributes }}>{{ $value }}</flux:textarea>
    @else
        <flux:input :id="$name" :name="$name" :type="$type" :value="$value" :required="$required" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $describedBy }}" {{ $attributes }} />
    @endif
    <flux:error :name="$name" id="{{ $name }}-error" :icon="false" />
</flux:field>
