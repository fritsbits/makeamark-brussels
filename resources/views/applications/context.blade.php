@if ($edition['status'] === 'open')
    <x-application-deadline :edition="$edition" class="application__context" />
    <p class="application__context application__context--lead">{{ $audience === 'makers' ? 'Tell us what you bring and when you can take part. Makers contribute their time voluntarily.' : 'Tell us about your organisation and the creative challenge you would like to work on. Taking part is free.' }}</p>
@else
    <p class="application__context application__context--lead">{{ $audience === 'makers' ? 'Makers contribute their time voluntarily. Selection happens in advance so that teams can be matched around each organisation’s challenge.' : 'Social organisations take part for free. Each application should focus on a concrete communication, design, or digital challenge.' }}</p>
@endif
@if ($audience === 'organisations')
    <p class="application__hint">You can answer in English, French or Dutch.</p>
@endif
