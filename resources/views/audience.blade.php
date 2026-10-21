<x-layouts.app :site="$site" :page-title="$pageTitle" :page-description="$pageDescription" :public-typography="true">
    @include('sections.nav')
    <main id="main" class="distilled audience-page {{ $audience === 'makers' ? 'makers-page' : 'organisations-page' }}" tabindex="-1">
        <section class="audience-hero container">
            @if ($audience === 'makers')
                <div class="audience-hero__heading audience-hero__heading--makers">
                    <flux:heading level="1" class="h-display audience-hero__title">Use your creative skills to make a difference.</flux:heading>
                </div>
            @else
                <div class="audience-hero__heading audience-hero__heading--organisations">
                    <flux:heading level="1" class="h-display audience-hero__title">Get a maker team to bring a long-waiting project to life.</flux:heading>
                </div>
            @endif
            <div class="audience-hero__support">
                <x-edition-date :date="$edition['startDate']" />
                <div class="audience-hero__details">
                    @if ($audience === 'makers')
                        <flux:text class="audience-hero__intro">Join a team of makers to work on a communication, design or digital challenge from a Brussels social organisation.</flux:text>
                    @else
                        <flux:text class="audience-hero__intro audience-hero__intro--lead">For the 2027 edition, we're teaming up with Brussels organisations that work with young people aged roughly 12–25.</flux:text>
                    @endif
                    @if ($edition['status'] === 'open')
                        <x-pill :href="$applicationUrl" :solid="true">Start your application</x-pill>
                    @else
                        <flux:text>{{ $edition['pill']['label'] }}</flux:text>
                        @if ($edition['status'] === 'preparing')
                            <x-pill :href="$site['instagramUrl']">Follow us on Instagram</x-pill>
                        @endif
                    @endif
                </div>
            </div>
        </section>

        <section class="commitment container">
            <div class="commitment__lead">
                <flux:heading level="2" class="h-display">What you commit to</flux:heading>
                @if ($audience === 'makers')
                    <flux:text>Contribute a full day pro-bono and join a planning meeting beforehand. We select makers and match teams in advance.</flux:text>
                @else
                    <flux:text>Taking part is free. Prepare your brief and materials, join a planning meeting, and take part in feedback and the evening presentation.</flux:text>
                @endif
            </div>
            <div class="commitment__steps">
                <article><span class="commitment__number h-display">01</span><flux:heading level="3" class="h-display">Apply</flux:heading><flux:text>{{ $audience === 'makers' ? 'Tell us about your skills, show examples of your work and share your availability.' : 'Tell us about your organisation and the challenge you want to work on.' }}</flux:text></article>
                <article><span class="commitment__number h-display">02</span><flux:heading level="3" class="h-display">Selection and preparation</flux:heading><flux:text>Applying does not guarantee selection. We review applications, match teams and arrange a planning meeting before the event.</flux:text></article>
                <article><span class="commitment__number h-display">03</span><flux:heading level="3" class="h-display">The day</flux:heading><flux:text>Work with your team on the brief, share feedback and present the work in the evening.</flux:text></article>
            </div>
        </section>

        <section id="application" class="application application--inline {{ $submitted ? 'application--submitted' : '' }} container" aria-labelledby="application-heading" tabindex="-1">
            @unless ($submitted)
            <div class="application__intro">
                <flux:heading level="2" id="application-heading" class="h-display application__title">{{ $audience === 'makers' ? 'Maker application' : 'Organisation application' }}</flux:heading>
                @include('applications.context')
                @include('applications.privacy-note')
            </div>
            @endunless
            @include('applications.intake', ['introRendered' => true])
        </section>
    </main>
    @include('sections.footer')
</x-layouts.app>
