@props(['audience' => null])
@inject('caseStudy', 'App\Content\SolumobCaseStudy')
@if ($caseStudy->isVisible() && ($audience === null || in_array($audience, ['makers', 'organisations'], true)))
    @if ($audience)
        <aside class="case-invitation container" aria-label="A previous Make a Mark collaboration">
            <flux:heading level="2" class="h-display case-invitation__title">{{ $audience === 'makers' ? 'Get a feel for the creative sprint.' : 'What can a day make?' }}</flux:heading>
            <div class="case-invitation__body">
                <flux:text>{{ $audience === 'makers' ? 'Meet the team behind Solumob’s website updates, photography and short film. See how the day unfolded.' : 'See how Solumob and four Makers turned a volunteer-recruitment challenge into practical communication materials.' }}</flux:text>
                <a class="case-link" href="{{ route('case-studies.solumob', absolute: false) }}"><span>{{ $audience === 'makers' ? 'Go behind the scenes with Solumob' : 'Explore the Solumob case' }}</span><flux:icon.arrow-right aria-hidden="true" /></a>
            </div>
        </aside>
    @else
        <section class="case-feature container" aria-labelledby="case-feature-heading">
            <div class="case-feature__copy">
                <flux:heading level="2" id="case-feature-heading" class="h-display case-feature__title">One day. Work out in the world.</flux:heading>
                <flux:text class="case-feature__meta">Solumob Volontaires · 2019</flux:text>
                <flux:text>A refreshed website, photography, a short film and tools for reaching volunteers. See what four Makers and Solumob created together.</flux:text>
                <x-pill :href="route('case-studies.solumob', absolute: false)">See what we made for Solumob</x-pill>
            </div>
            <a class="case-feature__visual" href="{{ route('case-studies.solumob', absolute: false) }}" tabindex="-1" aria-hidden="true">
                <img src="{{ route('case-studies.solumob.media', 'hero', false) }}" width="1200" height="675" alt="" loading="lazy">
            </a>
        </section>
    @endif
@endif
