<x-layouts.app :site="$site">
    @include('previews.messaging.original-nav')
    <main id="main" tabindex="-1">
        <section id="top" class="hero container hero--edition">
            <div class="hero__copy">
            <flux:text class="kicker">The 9th edition · Brussels · 27 March 2027</flux:text>
            <flux:heading level="1" class="h-display hero__headline">{{ $site['hero']['headline'] }}</flux:heading>
            <flux:text class="hero__intro">{{ $site['hero']['intro'] }}</flux:text>
            <div class="hero__actions">
                <x-pill href="/makers">For makers</x-pill>
                <x-pill href="/organisations">For social organisations</x-pill>
            </div>
            <p class="hero__status"><span class="status-dot" aria-hidden="true"></span>{{ $edition['pill']['label'] }}</p>
            </div>
            <figure class="hero__visual">
                <div class="hero__photos">
                    @foreach ($site['hero']['photos'] as $photo)
                        <div class="hero__photo">
                            <x-photo-reveal :file="$photo['file']" :widths="[480, 960]" :default-width="480" :alt="$photo['alt']" sizes="(max-width: 860px) 90vw, 42vw" width="1000" :height="$loop->first ? 636 : 486" loading="eager" />
                        </div>
                    @endforeach
                </div>
                <figcaption><a href="{{ $site['hero']['photoSourceUrl'] }}" target="_blank" rel="noopener">{{ $site['hero']['photoSourceLabel'] }}</a></figcaption>
            </figure>
        </section>
        <section class="how section-block block--red">
            <div class="container">
                <flux:text class="kicker">One day, one shared challenge</flux:text>
                <flux:heading level="2" class="h-display how__heading">Good work starts with a real brief.</flux:heading>
                <ol class="how__steps">
                    <li><span>01</span><h3>Bring the challenge</h3><p>A Brussels social organisation shares one communication, design, or digital challenge.</p></li>
                    <li><span>02</span><h3>Build a team</h3><p>Makers are selected and matched with an organisation around the work to be done.</p></li>
                    <li><span>03</span><h3>Make a useful start</h3><p>On the day, the team works together on a practical direction or first set of materials.</p></li>
                </ol>
            </div>
        </section>
        <section class="audience-pair container" aria-labelledby="audience-heading">
            <div class="audience-pair__intro">
                <flux:text class="kicker">Two sides of the same team</flux:text>
                <flux:heading level="2" class="h-display" id="audience-heading">Who will you be making a mark with?</flux:heading>
            </div>
            <article class="audience-panel">
                <p class="kicker">For makers</p><h3 class="h-display">Bring what you’re good at.</h3>
                <p>Give a day to a useful local challenge, meet other makers, and make something with the people who know the work best.</p>
                <x-pill href="/makers">Explore the maker journey</x-pill>
            </article>
            <article class="audience-panel audience-panel--lined">
                <p class="kicker">For social organisations</p><h3 class="h-display">Bring one real challenge.</h3>
                <p>Work alongside a team of makers on a communication, design, or digital challenge that matters to your organisation.</p>
                <x-pill href="/organisations">Explore the organisation journey</x-pill>
            </article>
        </section>
        <section class="youth-note section-block block--red">
            <div class="container youth-note__inner">
                <div><flux:text class="kicker">The 2027 focus</flux:text><flux:heading level="2" class="h-display">Young people are part of the 2027 edition.</flux:heading></div>
                <p>This edition centres on organisations working with young people in Brussels. Young people are important participants in shaping the work. The public applications are for makers and social organisations.</p>
            </div>
        </section>
        @include('sections.stories')
        @include('sections.edition')
        @include('sections.faq')
        @include('sections.about')
    </main>
    @include('previews.messaging.original-footer')
</x-layouts.app>
