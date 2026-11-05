@props(['name', 'label', 'prefix', 'hint' => null])
@php
    $previous = old($name, []);
    $previous = is_array($previous) ? array_filter($previous, 'is_string') : [];
@endphp
<div id="{{ $name }}" tabindex="-1" role="group" aria-labelledby="{{ $name }}-label" aria-describedby="{{ $name }}-error" x-bind:aria-describedby="missing.includes('{{ $name }}') ? '{{ $name }}-error {{ $name }}-client-error' : '{{ $name }}-error'">
    <flux:label id="{{ $name }}-label">{{ $label }} <span aria-hidden="true">*</span></flux:label>
    @if ($hint)<flux:text class="application-form__hint">{{ $hint }}</flux:text>@endif
    <div class="application-form__checks">
        @foreach (['English', 'Dutch', 'French'] as $language)
            <x-application.checkbox :name="$name.'[]'" id="{{ $prefix }}-{{ $loop->index }}" :value="$language" :checked="in_array($language, $previous, true)" :show-error="false" group-error="{{ $name }}-error">{{ $language }}</x-application.checkbox>
        @endforeach
    </div>
    <flux:error :name="$name" id="{{ $name }}-error" :icon="false" />
    <flux:text id="{{ $name }}-client-error" class="application-form__client-error" role="alert" x-cloak x-show="missing.includes('{{ $name }}')">Choose at least one language to continue.</flux:text>
</div>
