<section class="strip" aria-label="Photos from past editions">
    <ul class="strip__list" role="list" tabindex="0">
        @foreach ($stories as $story)
            <li class="strip__item">
                <x-photo-reveal :file="$story['file']" :widths="[240, 320, 480, 640]" :default-width="320" sizes="(max-width: 760px) 150px, 190px" :alt="$story['alt']" width="320" height="569" :loading="$loop->index < 4 ? 'eager' : 'lazy'" />
                <span class="visually-hidden">Photo by {{ $story['credit'] }}</span>
            </li>
        @endforeach
    </ul>
    <div class="strip__cta">
        <x-pill :href="$site['instagramUrl']">{{ $site['hero']['instagramLabel'] }}</x-pill>
    </div>
</section>
