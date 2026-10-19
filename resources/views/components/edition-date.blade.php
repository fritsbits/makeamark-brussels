@props(['date'])

@php($editionDate = new DateTimeImmutable($date))

<time {{ $attributes->class(['edition-date']) }} datetime="{{ $editionDate->format('Y-m-d') }}">
    <span class="sr-only">{{ $editionDate->format('l j F Y') }}</span>
    <span class="edition-date__weekday" aria-hidden="true">{{ $editionDate->format('l') }}</span>
    <span class="edition-date__row" aria-hidden="true">
        <span class="edition-date__day">{{ $editionDate->format('j') }}</span>
        <span class="edition-date__copy"><span>{{ $editionDate->format('F') }}</span><span>{{ $editionDate->format('Y') }}</span></span>
    </span>
</time>
