<section id="about" class="about container">
    <div class="about__text">
        <flux:heading level="2" class="h-display about__heading">{{ $site['about']['heading'] }}</flux:heading>
        <flux:text>{{ $site['about']['intro'] }}</flux:text>
        <ul class="about__people" role="list">
            @foreach ($site['about']['organisers'] as $person)
                <li><a href="{{ $person['url'] }}" rel="noopener" target="_blank">{{ $person['name'] }}</a> <span>{{ $person['role'] }}</span></li>
            @endforeach
        </ul>
        <flux:text class="about__contact"><x-pill :href="'mailto:'.$site['email']">{{ $site['about']['contactLabel'] }}</x-pill></flux:text>
    </div>
    <div class="about__logo">
        <img src="/images/logo-brussels.420.webp" srcset="/images/logo-brussels.420.webp 420w, /images/logo-brussels.840.webp 840w" sizes="(max-width: 860px) 280px, 420px" alt="Make a Mark Brussels, hand-drawn logo" width="420" height="407" loading="lazy">
    </div>
</section>
