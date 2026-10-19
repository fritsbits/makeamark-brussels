<section id="edition" class="section-block block--red edition">
    <div class="container edition__grid">
        <div class="edition__text">
            <flux:text class="kicker">{{ $edition['kicker'] }}</flux:text>
            <flux:heading level="2" class="h-display edition__title">{{ $edition['title'] }}</flux:heading>
            <flux:text class="edition__dates">{{ $edition['dates'] }}</flux:text>
            <div class="edition__body">{!! $edition['html'] !!}</div>
            @if (!empty($edition['callToAction']))
                <flux:text class="edition__cta">{{ $edition['callToAction'] }}</flux:text>
            @endif
            @if ($edition['pill']['href'])
                <x-pill :href="$edition['pill']['href']" :solid="$edition['status'] === 'open'">{{ $edition['pill']['label'] }}</x-pill>
            @else
                <p class="edition__status kicker" role="status">{{ $edition['pill']['label'] }}</p>
            @endif
        </div>
        <div class="edition__photo">
            <x-photo-reveal class="photo-reveal--edition" :file="$edition['photo']" :widths="[440, 880]" sizes="(max-width: 700px) 90vw, (max-width: 860px) 600px, (max-width: 1200px) 46vw, 600px" :alt="$edition['photoAlt']" label="group photo" width="440" :height="$edition['photoHeight']" loading="lazy" />
        </div>
    </div>
</section>
