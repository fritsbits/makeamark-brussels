<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Briefing for Makers · {{ $briefing['name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="print-page">
    <main class="print-sheet">
        <div class="print-sheet__toolbar">
            <flux:button variant="primary" icon="printer" x-data x-on:click="window.print()">Print briefing</flux:button>
        </div>
        <p class="print-sheet__kicker">Make a Mark Brussels {{ $briefing['edition'] }}</p>
        <flux:heading level="1" class="print-sheet__title">{{ $briefing['name'] }}</flux:heading>

        <section class="print-sheet__section">
            <flux:heading level="2" class="print-sheet__heading">Briefing for Makers</flux:heading>
            @if (filled($briefing['brief']))
                <div class="print-sheet__body">{{ $briefing['brief'] }}</div>
            @else
                <p class="print-sheet__empty">No refined briefing saved yet.</p>
            @endif
        </section>

        @foreach ([['Skills needed', $briefing['skills']], ['Themes', $briefing['themes']], ['Languages', $briefing['languages']]] as [$heading, $items])
            @if ($items)
                <section class="print-sheet__section">
                    <flux:heading level="2" class="print-sheet__heading">{{ $heading }}</flux:heading>
                    <ul class="print-sheet__list">
                        @foreach ($items as $item)<li>{{ $item }}</li>@endforeach
                    </ul>
                </section>
            @endif
        @endforeach
    </main>
    @livewireScripts
    @fluxScripts
</body>
</html>
