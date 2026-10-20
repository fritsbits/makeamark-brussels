<header @class(['nav', 'nav--homepage' => request()->routeIs('home')]) x-data="siteNavigation" :class="{ 'is-open': open, 'is-compact': compact }" @scroll.window.throttle.50ms="updateCompact()" @keydown.escape.window="close()" @keydown.tab="trapFocus($event)">
    <div class="container nav__inner">
        <a class="nav__wordmark h-display" href="/">{{ $site['name'] }}</a>
        <flux:button class="nav__toggle" variant="ghost" aria-expanded="false" ::aria-expanded="open.toString()" aria-controls="nav-links" aria-label="Menu" x-ref="toggle" @click="toggle()"><i aria-hidden="true"></i></flux:button>
        <nav id="nav-links" class="nav__links" aria-label="Sections" x-ref="links">
            @foreach ($site['nav'] as $item)
                <a href="{{ str_starts_with($item['href'], '#') && ! request()->routeIs('home') ? '/'.$item['href'] : $item['href'] }}" @click="close(false)">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </div>
</header>
