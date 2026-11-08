<x-layouts.app :site="$site" :page-title="$pageTitle" :page-description="$pageDescription" :public-typography="true" canonical-path="/case-studies/solumob" og-type="article" social-image="/case-studies/solumob/media/hero" social-image-alt="Solumob’s volunteer-focused website design, created at Make a Mark Brussels in 2019." :social-image-height="675">
    @include('sections.nav')
    <main id="main" class="case-study" tabindex="-1">
        <article>
            <header class="case-hero container">
                <div>
                    <a class="back-link" href="/"><flux:icon.arrow-left variant="micro" class="back-link__icon" aria-hidden="true" /><span>Back to the edition</span></a>
                    <flux:heading level="1" class="h-display case-hero__title"><span>One day.</span> <span>Four Makers.</span> <span>More than a slide deck.</span></flux:heading>
                    <flux:text class="case-meta">Solumob Volontaires · Brussels · 2019</flux:text>
                    <flux:text class="case-hero__intro">A refreshed website, original photography, a short film, and a collection of ready-to-use communication materials. All created in one day to help Solumob attract more volunteers.</flux:text>
                    <flux:text class="case-meta">1 day · 4 Makers · 1 social organisation</flux:text>
                    <a class="case-link" href="#made"><span>Look what we made</span><flux:icon.arrow-down aria-hidden="true" /></a>
                </div>
                <figure class="case-hero__visual">
                    <div class="case-hero__website"><img src="{{ route('case-studies.solumob.media', 'website-after', false) }}" alt="Solumob’s updated website design, with blue and yellow graphics, original photography and an invitation to volunteer." width="658" height="2500" fetchpriority="high"></div>
                    <img class="case-hero__social" src="{{ route('case-studies.solumob.media', 'volunteer-story', false) }}" alt="A volunteer testimonial design featuring Sabine in the same blue and yellow visual identity." width="940" height="788">
                    <figcaption class="case-caption">Website and social designs from the team’s presentation.</figcaption>
                </figure>
            </header>

            <section class="case-split case-section container" aria-labelledby="challenge">
                <flux:heading level="2" class="h-display case-section__title" id="challenge">More volunteers,<br>more freedom.</flux:heading>
                <div class="case-prose">
                    <flux:text>For people with reduced mobility, a ride to the doctor, the shops or a family visit can make all the difference.</flux:text>
                    <flux:text>Solumob Volontaires connects these people with volunteer drivers who offer more than transport: companionship and personal attention along the way.</flux:text>
                    <flux:text>But demand was growing faster than the volunteer network. Solumob needed to attract more drivers in Brussels and make its work better known.</flux:text>
                    <flux:text>That’s where Make a Mark came in.</flux:text>
                </div>
            </section>

            <section id="made" class="case-results case-section container" aria-labelledby="made-heading">
                <div class="case-opening">
                    <flux:heading level="2" class="h-display case-section__title" id="made-heading">Look what we made.</flux:heading>
                    <flux:text class="case-prose">Four Makers brought their skills together to tackle Solumob’s communication challenge. By the end of the day, they had created a collection of materials spanning strategy, design, photography, video and digital communication.</flux:text>
                </div>
                <section class="case-output" aria-labelledby="website">
                    <div class="case-split case-output__intro">
                        <flux:heading level="3" class="h-display case-output__title" id="website">A website updated on the spot.</flux:heading>
                        <flux:text class="case-prose">Rather than stopping at a design proposal, the team got access to Solumob’s existing website and made changes directly. By the end of the day, new messaging and visuals were already live, with a stronger focus on attracting volunteers.</flux:text>
                    </div>
                    <div class="case-comparison">
                        @foreach ([['website-before', 'Before', 1247, 'The existing website, led by a photograph and a long introduction to the organisation.'], ['website-after', 'Volunteer-focused update', 658, 'The updated design puts volunteer recruitment, people’s stories and ways to get involved at the centre.']] as [$image, $label, $width, $alt])
                            <figure><figcaption class="case-comparison__label">{{ $label }}</figcaption><a class="case-browser" href="{{ route('case-studies.solumob.media', $image, false) }}" target="_blank" rel="noopener" aria-label="{{ 'View the complete '.$label.' website image in a new tab' }}"><img src="{{ route('case-studies.solumob.media', $image, false) }}" alt="{{ $alt }}" width="{{ $width }}" height="2500" loading="lazy"></a></figure>
                        @endforeach
                    </div>
                    <flux:text class="case-caption">The website comparison from the team’s presentation. Select either image to view the complete page.</flux:text>
                </section>
                <section class="case-story case-split case-output" aria-labelledby="story">
                    <div><flux:heading level="3" class="h-display case-output__title" id="story">A clearer story to tell.</flux:heading><flux:text class="case-prose">The team developed a communication plan, refreshed elements of the existing visual identity and created a new tagline: <span lang="fr">“Votre mobilité, notre bonheur”</span> — capturing the joy of helping others.</flux:text></div>
                    <p class="case-story__line" lang="fr">Votre mobilité,<br>notre bonheur.</p>
                </section>
                <section class="case-output" aria-labelledby="toolkit">
                    <div class="case-split case-output__intro"><flux:heading level="3" class="h-display case-output__title" id="toolkit">Communication materials ready to use.</flux:heading><flux:text class="case-prose">Facebook recruitment posts, testimonial formats, a new cover image and reusable Canva templates gave Solumob practical tools to continue reaching potential volunteers.</flux:text></div>
                    <figure class="case-cover"><img src="{{ route('case-studies.solumob.media', 'facebook-cover', false) }}" alt="Solumob’s Facebook page design with a new cover inviting people to join the volunteer team." width="2358" height="1530" loading="lazy"><figcaption class="case-caption">The volunteer-recruitment cover, shown in the Facebook page layout.</figcaption></figure>
                    <div class="case-socials">
                        @foreach ([['beneficiary-story', 'A yellow testimonial design about visiting a friend independently.', 'Beneficiary story format.'], ['volunteer-story', 'A blue volunteer testimonial design featuring Sabine.', 'Volunteer story format.'], ['recruitment-post', 'A recruitment post linking an accessible music festival with the need for volunteer drivers.', 'An invitation to help people get there.']] as [$image, $alt, $caption])
                            <figure><img src="{{ route('case-studies.solumob.media', $image, false) }}" alt="{{ $alt }}" width="940" height="788" loading="lazy"><figcaption class="case-caption">{{ $caption }}</figcaption></figure>
                        @endforeach
                    </div>
                    <flux:text class="case-caption">Selected designs from the final presentation. Testimonials are shown as part of the original design work.</flux:text>
                </section>
                <section class="case-split case-output case-photography" aria-labelledby="film">
                    <div><flux:heading level="3" class="h-display case-output__title" id="film">Original photography and a short film.</flux:heading><flux:text class="case-prose">With a volunteer driver and participants available on the day, the team organised a photoshoot and filmed scenes illustrating Solumob’s service. A 56-second film was edited and screened that same evening.</flux:text></div>
                    <figure><img src="{{ route('case-studies.solumob.media', 'photoshoot', false) }}" alt="A photographer makes portraits beside the Solumob car during the make-a-thon." width="1280" height="720" loading="lazy"><figcaption class="case-caption">Making the photographs together. Still from Frederik’s vlog, 01:38.</figcaption></figure>
                </section>
            </section>

            <section class="case-making" aria-labelledby="making"><div class="container">
                <div class="case-split"><flux:heading level="2" class="h-display case-section__title" id="making">Behind the scenes:<br>making it happen.</flux:heading><div class="case-prose">
                    <flux:text>The day moved quickly. From exploring target audiences and developing messages to shooting footage, editing video, designing social posts and updating a live website.</flux:text>
                    <flux:text>Solumob coordinator Cindy Bertijn joined the team at several points throughout the day, offering immediate feedback on the creative direction. A volunteer driver contributed firsthand knowledge of the service and helped make the photography and video possible.</flux:text>
                    <flux:text>With the deadline approaching, the Makers were still finishing the website and preparing their presentation. That evening, Cindy joined the team to present the results.</flux:text>
                </div></div>
                <div class="case-video" x-data="{ playing: false }">
                    <div class="case-video__poster" x-show="!playing"><img src="{{ route('case-studies.solumob.media', 'editing', false) }}" alt="Editing the film during the event, from Frederik’s making-of vlog." width="1280" height="720" loading="lazy"><flux:button class="case-video__play" icon="play" @click="playing = true" x-cloak>Play the making-of</flux:button></div>
                    <template x-if="playing"><iframe class="case-video__player" :src="'https://www.youtube-nocookie.com/embed/M6fTa72AHuo?autoplay=1'" title="Solumob at Make a Mark Brussels 2019, behind the scenes by Frederik Vincx" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe></template>
                </div>
                <div class="case-making__caption"><a href="https://www.youtube.com/watch?v=M6fTa72AHuo" target="_blank" rel="noopener">Watch the making-of on YouTube <flux:icon.arrow-up-right aria-hidden="true" /></a></div>
            </div></section>

            <section class="case-split case-section container" aria-labelledby="afterwards"><flux:heading level="2" class="h-display case-section__title" id="afterwards">And afterwards?</flux:heading><div class="case-prose">
                <flux:text>Some of the work was already implemented before the make-a-thon ended. Solumob subsequently featured the short film on its website.</flux:text>
                <flux:text>The following year, the organisation applied to Make a Mark again, reporting very positive feedback on the work of the four Makers.</flux:text>
                <flux:text>The project is a reminder that a make-a-thon doesn’t have to end with ideas and presentations. Sometimes, the results are already out in the world before the day is over.</flux:text>
            </div></section>
            <section class="case-split case-credits container" aria-label="Project credits"><div><flux:heading level="2" class="h-display case-output__title">The Makers</flux:heading><ul role="list"><li>Emilie Macarini</li><li>Annelies Bruneel</li><li>Ellen Daenen</li><li>Frederik Vincx</li></ul></div><div><flux:heading level="2" class="h-display case-output__title">The organisation</flux:heading><flux:text>Solumob Volontaires, represented by Cindy Bertijn, with the participation of a volunteer driver.</flux:text><flux:text class="case-caption">Designs from the team’s 2019 presentation. Making-of video and documentary stills by Frederik Vincx.</flux:text></div></section>
            <section class="case-next" aria-labelledby="your-turn"><div class="container"><flux:heading level="2" class="h-display case-section__title" id="your-turn">Your turn?</flux:heading><div class="case-next__paths">
                <div><flux:heading level="3" class="h-display case-output__title">Have a challenge for our Makers?</flux:heading><flux:text>Discover how Make a Mark can help your organisation.</flux:text><x-pill href="/organisations">Explore organisation details</x-pill></div>
                <div><flux:heading level="3" class="h-display case-output__title">Want to put your creative skills to good use?</flux:heading><flux:text>Join us for the next make-a-thon.</flux:text><x-pill href="/makers">Explore Maker details</x-pill></div>
            </div></div></section>
        </article>
    </main>
    @include('sections.footer')
</x-layouts.app>
