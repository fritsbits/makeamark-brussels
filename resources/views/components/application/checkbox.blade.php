@props(['name', 'id', 'value' => '1', 'checked' => false, 'required' => false, 'showError' => true, 'groupError' => 'themes-error'])
@php
    $errorKey = str_ends_with($name, '[]') ? substr($name, 0, -2) : $name;
    $invalid = $errors->has($errorKey) || $errors->has($errorKey.'.*');
    $descriptionExpression = $required
        ? "missing.includes('{$id}') ? '{$id}-error {$id}-client-error' : '{$id}-error'"
        : ($groupError === 'languages-error'
            ? "missing.includes('languages') ? 'languages-error languages-client-error' : 'languages-error'"
            : \Illuminate\Support\Js::from($showError ? $id.'-error' : $groupError)->toHtml());
@endphp
<div id="{{ $id }}" class="application-form__choice" tabindex="-1">
    <label class="application-form__choice-row application-form__enhanced">
        <flux:checkbox id="{{ $id }}-control" :name="$name" :value="$value" :checked="$checked" :required="$required"
            :aria-required="$required ? 'true' : 'false'" :aria-invalid="$invalid ? 'true' : 'false'"
            aria-labelledby="{{ $id }}-label" aria-describedby="{{ $showError ? $id.'-error' : $groupError }}"
            x-bind:aria-describedby="{{ $descriptionExpression }}"
            data-validation-name="{{ $id }}" />
        <span id="{{ $id }}-label">{{ $slot }} @if ($required)<span aria-hidden="true">*</span>@endif</span>
    </label>
    <noscript>
        <label class="application-form__choice-row" for="{{ $id }}-native">
            <input type="checkbox" id="{{ $id }}-native" name="{{ $name }}" value="{{ $value }}" @checked($checked) @required($required) @if ($invalid) aria-invalid="true" @endif aria-describedby="{{ $showError ? $id.'-error' : $groupError }}">
            <span>{{ $slot }} @if ($required)<span aria-hidden="true">*</span>@endif</span>
        </label>
    </noscript>
    @if ($showError)<flux:error :name="$name" id="{{ $id }}-error" :icon="false" />@endif
    @if ($required)
        <flux:text id="{{ $id }}-client-error" class="application-form__client-error" role="alert" x-cloak x-show="missing.includes('{{ $id }}')">Please confirm this commitment to continue.</flux:text>
    @endif
</div>
