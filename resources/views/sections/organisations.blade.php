<section id="organisations" class="section-block block--red orgs" aria-labelledby="orgs-heading">
    <div class="container">
        <flux:heading level="2" class="h-display orgs__heading" id="orgs-heading">{{ $site['organisations']['heading'] }}</flux:heading>
        <flux:text class="orgs__intro">{{ $site['organisations']['intro'] }}</flux:text>
        <ul class="orgs__list" role="list">
            @foreach ($organisations as $org)
                <li class="org">@if (!empty($org['url']))<a href="{{ $org['url'] }}" rel="noopener" target="_blank">{{ $org['name'] }}</a>@else{{ $org['name'] }}@endif</li>
            @endforeach
        </ul>
    </div>
</section>
