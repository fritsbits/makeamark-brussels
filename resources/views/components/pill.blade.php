@props(['href' => null, 'solid' => false, 'action' => null])
@if ($href || $action)
    <flux:button :href="$href" :x-data="$action ? '' : null" :x-on:click="$action" variant="ghost" :class="$solid ? 'pill pill--solid' : 'pill'" :target="$href && str_starts_with($href, 'http') ? '_blank' : null" :rel="$href && str_starts_with($href, 'http') ? 'noopener' : null" {{ $attributes->only('class') }}>
        <span class="pill__content">{{ $slot }}<flux:icon :name="$action ? 'printer' : 'arrow-up-right'" class="pill__icon" aria-hidden="true" /></span>
        <span class="pill__sweep" aria-hidden="true">
            <span class="pill__content">{{ $slot }}<flux:icon :name="$action ? 'printer' : 'arrow-up-right'" class="pill__icon" aria-hidden="true" /></span>
        </span>
    </flux:button>
@else
    <span class="pill"><span>{{ $slot }}</span></span>
@endif
