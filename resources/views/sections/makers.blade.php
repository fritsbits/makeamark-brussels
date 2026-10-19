<section id="makers" class="makers container">
    <flux:heading level="2" class="h-display makers__heading">{{ $site['makers']['heading'] }}</flux:heading>
    <ol class="makers__steps" role="list">
        @foreach ($site['makers']['steps'] as $step)
            <li class="makers__step">
                <span class="h-display h-display--light makers__num" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>
                <flux:heading level="3" class="h-display makers__title">{{ $step['title'] }}</flux:heading>
                <flux:text>{{ $step['text'] }}</flux:text>
            </li>
        @endforeach
    </ol>
    <flux:text class="makers__closing">{{ $site['makers']['closing'] }}</flux:text>
</section>
