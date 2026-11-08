<x-layouts.app :site="$site" :page-title="$pageTitle" :page-description="$pageDescription" :public-typography="true">
    @include('sections.nav')
    <main id="main" class="distilled audience-page {{ $audience }}-page" tabindex="-1">
        <section class="audience-hero container">
            @include('audiences.'.$audience.'.heading')
            <div class="audience-hero__support">
                <x-edition-date :date="$edition['startDate']" />
                <div class="audience-hero__details">
                    @include('audiences.'.$audience.'.intro')
                    @if ($applicationsOpen)
                        @if ($definition->followsEditionStatus)
                            <x-application-deadline :edition="$edition" class="audience-hero__deadline" />
                        @endif
                        <x-pill :href="$applicationUrl" :solid="true">Start your application</x-pill>
                    @elseif ($definition->followsEditionStatus)
                        <flux:text>{{ $edition['pill']['label'] }}</flux:text>
                        <x-application-deadline :edition="$edition" class="audience-hero__deadline" />
                        @if ($edition['status'] === 'preparing')
                            <x-pill :href="$site['instagramUrl']">Follow us on Instagram</x-pill>
                        @endif
                    @else
                        <flux:text>Volunteer applications are closed for {{ $edition['year'] }}</flux:text>
                        <x-pill :href="$site['instagramUrl']">Follow us on Instagram</x-pill>
                    @endif
                </div>
            </div>
        </section>

        @include('audiences.'.$audience.'.sections')

        <x-case-study-invitation :audience="$audience" />

        <section id="application" class="application application--inline {{ $submitted ? 'application--submitted' : '' }} container" aria-labelledby="application-heading" tabindex="-1">
            @unless ($submitted)
            <div class="application__intro">
                <flux:heading level="2" id="application-heading" class="h-display application__title">{{ ucfirst($definition->noun) }} application</flux:heading>
                @include('applications.context')
                @include('applications.privacy-note')
            </div>
            @endunless
            @include('applications.intake', ['introRendered' => true])
        </section>
    </main>
    @include('sections.footer')
</x-layouts.app>
