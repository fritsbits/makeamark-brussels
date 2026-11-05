@props(['label'])
@php
    $previous = old('themes', []);
    $previous = is_array($previous) ? array_filter($previous, 'is_string') : [];
@endphp
<div id="themes" tabindex="-1" aria-labelledby="themes-label">
    <flux:label id="themes-label">{{ $label }}</flux:label>
    <div class="application-form__checks">
        @foreach (\App\Support\Themes::all() as $theme)
            <x-application.checkbox name="themes[]" id="theme-{{ $loop->index }}" :value="$theme" :checked="in_array($theme, $previous, true)" :show-error="false">{{ $theme }}</x-application.checkbox>
        @endforeach
    </div>
    <flux:error name="themes" id="themes-error" :icon="false" />
</div>
