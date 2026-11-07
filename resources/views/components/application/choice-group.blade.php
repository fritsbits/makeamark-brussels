@props(['name', 'label', 'prefix', 'options', 'hint' => null, 'clientError' => 'Choose at least one option to continue.'])
@php
    $previous = old($name, []);
    $previous = is_array($previous) ? array_filter($previous, 'is_string') : [];
@endphp
<div id="{{ $name }}" tabindex="-1" role="group" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $name }}-error" x-bind:aria-describedby="missing.includes('{{ $name }}') ? '{{ $name }}-error {{ $name }}-client-error' : '{{ $name }}-error'">
    <flux:label id="{{ $name }}-label">{{ $label }} <span aria-hidden="true">*</span></flux:label>
    @if ($hint)<flux:text class="application-form__hint">{{ $hint }}</flux:text>@endif
    <div class="application-form__checks">
        @foreach ($options as $value => $optionLabel)
            <x-application.checkbox :name="$name.'[]'" id="{{ $prefix }}-{{ $loop->index }}" :value="$value" :checked="in_array($value, $previous, true)" :show-error="false" group-error="{{ $name }}-error">{{ $optionLabel }}</x-application.checkbox>
        @endforeach
    </div>
    <flux:error :name="$name" id="{{ $name }}-error" :icon="false" />
    <flux:text id="{{ $name }}-client-error" class="application-form__client-error" role="alert" x-cloak x-show="missing.includes('{{ $name }}')">{{ $clientError }}</flux:text>
</div>
