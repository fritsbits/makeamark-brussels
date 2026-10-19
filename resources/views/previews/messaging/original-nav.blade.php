<header class="nav" x-data="{ open: false }" :class="{ 'is-open': open }" @keydown.escape.window="open = false">
    <div class="container nav__inner">
        <a class="nav__wordmark h-display" href="/tools/messaging/original">{{ $site['name'] }}</a>
        <flux:button class="nav__toggle" variant="ghost" aria-expanded="false" ::aria-expanded="open.toString()" aria-controls="nav-links" aria-label="Menu" @click="open = !open"><i aria-hidden="true"></i></flux:button>
        <nav id="nav-links" class="nav__links" aria-label="Sections">
            @foreach ($site['nav'] as $item)
                <a href="{{ str_starts_with($item['href'], '#') && ! request()->routeIs('home') ? '/tools/messaging/original'.$item['href'] : $item['href'] }}" @click="open = false">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </div>
</header>
