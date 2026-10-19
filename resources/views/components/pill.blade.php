@props(['href' => null, 'solid' => false])
@if ($href)
    <flux:button :href="$href" variant="ghost" :class="$solid ? 'pill pill--solid' : 'pill'" :target="str_starts_with($href, 'http') ? '_blank' : null" :rel="str_starts_with($href, 'http') ? 'noopener' : null">
        <span class="pill__content">{{ $slot }}<flux:icon.arrow-up-right class="pill__icon" aria-hidden="true" /></span>
        <span class="pill__sweep" aria-hidden="true">
            <span class="pill__content">{{ $slot }}<flux:icon.arrow-up-right class="pill__icon" aria-hidden="true" /></span>
        </span>
    </flux:button>
@else
    <span class="pill"><span>{{ $slot }}</span></span>
@endif
