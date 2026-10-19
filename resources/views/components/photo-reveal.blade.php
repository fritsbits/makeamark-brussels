@props(['file', 'alt', 'widths' => [320], 'defaultWidth' => null, 'sizes' => '100vw', 'width', 'height', 'loading' => 'lazy', 'label' => null])
@php($label ??= $alt)
<button type="button" {{ $attributes->class(['photo-reveal']) }}
    x-data="photoReveal" disabled :disabled="!ready" :data-ready="ready"
    :class="{ 'is-revealed': hovered || pinned }"
    @pointerenter="enter($event)" @pointerleave="leave()" @pointercancel="leave()"
    @click="toggle()" @keydown.escape.window="restore()"
    aria-label="Show original photo: {{ $label }}"
    :aria-label="pinned ? @js('Return to desaturated photo: '.$label) : @js('Show original photo: '.$label)"
    aria-pressed="false" :aria-pressed="pinned">
    <span class="photo-frame photo-reveal__frame">
        <span class="photo-reveal__photo" x-ref="photo">
            <x-photo :file="$file" :widths="$widths" :default-width="$defaultWidth" treatment="monochrome" :sizes="$sizes" :alt="$alt" :width="$width" :height="$height" :loading="$loading" />
            <x-photo class="photo-reveal__original" x-ref="original" :file="$file" :widths="$widths" :default-width="$defaultWidth" :sizes="$sizes" alt="" aria-hidden="true" :width="$width" :height="$height" :loading="$loading" />
        </span>
    </span>
</button>
