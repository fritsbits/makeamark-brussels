@props(['edition'])

@if ($edition['deadline'] ?? null)
    <p {{ $attributes->class(['application-deadline']) }}>Apply by <time datetime="{{ $edition['deadline']->format('Y-m-d') }}">{{ $edition['deadline']->format('j F Y') }}</time></p>
@endif
