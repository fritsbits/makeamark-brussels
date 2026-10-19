<x-layouts.app :site="$site" :public-typography="true">
    @include('sections.nav')
    <main id="main" class="distilled" tabindex="-1">
        <section id="top" class="hero container hero--edition">
            <div class="hero__copy">
                <flux:heading level="1" class="h-display hero__headline">
                    @foreach (preg_split('/(?<=\.)\s+/', $site['hero']['headline']) as $line)
                        <span class="hero__headline-line">{{ $line }}</span>
                    @endforeach
                </flux:heading>
                <flux:text class="hero__intro distilled__lead">{{ $site['hero']['intro'] }}</flux:text>
            </div>
            <figure class="hero__visual">
                <div class="hero__photos">
                    @foreach ($site['hero']['photos'] as $photo)
                        <div class="hero__photo">
                            <x-photo-reveal :file="$photo['file']" :widths="[480, 960]" :default-width="480" :alt="$photo['alt']" sizes="(max-width: 860px) 90vw, 42vw" width="1000" :height="$loop->first ? 636 : 486" loading="eager" />
                        </div>
                    @endforeach
                </div>
            </figure>
            <a class="hero__status" href="#take-part" aria-label="{{ $edition['status'] === 'open' ? 'Apply now. Explore ways to take part.' : $edition['pill']['label'].'. Explore ways to take part.' }}">
                @if ($edition['status'] === 'open')
                    <span class="hero__status-art" aria-hidden="true"></span>
                @else
                    <span class="hero__status-label"><span class="status-dot" aria-hidden="true"></span>{{ $edition['pill']['label'] }}</span>
                @endif
            </a>
        </section>


        <section id="take-part" class="audience-pair container" aria-label="Take part">
            <article class="audience-panel">
                <flux:heading level="2" class="h-display">Makers,</flux:heading>
                <flux:text>Give your creative skills to a local project and meet other makers.</flux:text>
                <x-pill href="/makers">Maker details</x-pill>
            </article>
            <article class="audience-panel">
                <flux:heading level="2" class="h-display">Social organisations,</flux:heading>
                <flux:text>Work with a maker team on one communication, design or digital challenge.</flux:text>
                <x-pill href="/organisations">Organisation details</x-pill>
            </article>
        </section>

        <section id="edition" class="section-block block--red" aria-labelledby="edition-heading">
            <div class="container distilled-edition">
                <div>
                    <flux:heading level="2" class="h-display distilled__heading" id="edition-heading">The 2027 edition</flux:heading>
                    <x-edition-date :date="$edition['startDate']" />
                </div>
                <div>
                    <div class="edition__body distilled__lead">{!! $edition['html'] !!}</div>
                    @if ($edition['status'] !== 'open')
                        <flux:text>{{ $edition['pill']['label'] }}</flux:text>
                    @endif
                </div>
            </div>
        </section>

        <section class="container distilled-evidence" aria-labelledby="evidence-heading">
            <flux:heading level="2" class="h-display distilled__heading" id="evidence-heading">A feel for the day.</flux:heading>
            <div class="distilled-evidence__stage" x-data="scrollPhoto">
                <div class="day-carousel" x-data="dayCarousel" role="region" aria-roledescription="carousel" aria-label="Photos from the day"
                    @keydown.right.prevent="go(1)" @keydown.left.prevent="go(-1)" @keydown.home.prevent="show(0)" @keydown.end.prevent="show(total - 1)">
                    <div class="distilled-evidence__photo">
                        <div class="day-carousel__track" id="day-photos" x-ref="track" @scroll.debounce.100ms="sync()" tabindex="0" aria-label="Day photos, scroll to explore">
                            @foreach ($edition['gallery'] ?? [['file' => $edition['photo'], 'alt' => $edition['photoAlt']]] as $photo)
                                <div class="day-carousel__slide" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $loop->count }}">
                            <x-photo-reveal :file="$photo['file']" :widths="[440, 880, 1200]" sizes="(max-width: 1360px) 94vw, 1280px" :alt="$photo['alt']" :label="'photo from the day, '.$loop->iteration.' of '.$loop->count" width="440" :height="$edition['photoHeight']" loading="lazy" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="day-carousel__footer">
                        <flux:text class="distilled-evidence__caption">Photos from past editions of Make a Mark.</flux:text>
                        <div class="day-carousel__controls" x-cloak>
                            <flux:button class="day-carousel__arrow" variant="ghost" square icon="arrow-left" aria-label="Previous photo" aria-controls="day-photos" @click="go(-1)" ::disabled="current === 0" />
                            <flux:button class="day-carousel__arrow" variant="ghost" square icon="arrow-right" aria-label="Next photo" aria-controls="day-photos" @click="go(1)" ::disabled="current === total - 1" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="distilled-evidence__links">
                    <flux:text><a class="evidence-resource" href="https://www.bruzz.be/samenleving/creatieve-marathon-make-mark-maakt-brussel-opnieuw-een-beetje-beter-2023-03-19" target="_blank" rel="noopener"><span>Watch the BRUZZ report from the 2023 edition</span><flux:icon.arrow-up-right class="evidence-resource__icon" aria-hidden="true" /></a></flux:text>
                    <flux:text><a class="evidence-resource" href="{{ $site['instagramUrl'] }}" target="_blank" rel="noopener"><span>More from past editions on Instagram</span><flux:icon.arrow-up-right class="evidence-resource__icon" aria-hidden="true" /></a></flux:text>
            </div>
        </section>

        @include('sections.organisations')

        <section id="about" class="about container">
            <flux:heading level="2" class="h-display about__heading">The people behind Make a Mark</flux:heading>
            <div class="about__text">
                <ul class="about__people" role="list">
                    @foreach ($site['about']['organisers'] as $person)
                        <li><a href="{{ $person['url'] }}" rel="noopener" target="_blank">{{ $person['name'] }}</a> <span>{{ $person['role'] }}</span></li>
                    @endforeach
                </ul>
                <x-pill :href="'mailto:'.$site['email']">Contact us</x-pill>
            </div>
            <div class="about__logo">
                <img src="/images/logo-brussels.420.webp" srcset="/images/logo-brussels.420.webp 420w, /images/logo-brussels.840.webp 840w" sizes="(max-width: 860px) 280px, 420px" alt="Make a Mark Brussels, hand-drawn logo" width="420" height="407" loading="lazy">
            </div>
        </section>
    </main>
    @include('sections.footer')
</x-layouts.app>
