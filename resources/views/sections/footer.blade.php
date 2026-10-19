<footer class="footer">
    <div class="container footer__inner">
        <div>
            <div class="footer__wordmark h-display">{{ $site['name'] }}</div>
            <a class="footer__global" href="{{ $site['globalSiteUrl'] }}" rel="noopener" target="_blank">{{ $site['footer']['globalLabel'] }}</a>
            <p class="footer__credit">Website by <a href="https://frederikvincx.com">Impact Studio</a></p>
        </div>
        <nav class="footer__links" aria-label="Footer">
            <div class="footer__group">
                @foreach ($site['nav'] as $item)
                    <a href="{{ str_starts_with($item['href'], '#') && ! request()->routeIs('home') ? '/'.$item['href'] : $item['href'] }}">{{ $item['label'] }}</a>
                @endforeach
            </div>
            <div class="footer__group">
                <a href="{{ $site['globalSiteUrl'] }}" rel="noopener" target="_blank">Organise in your city</a>
                <a href="{{ $site['instagramUrl'] }}" rel="noopener" target="_blank">Instagram</a>
            </div>
            <div class="footer__group">
                <a href="{{ route('privacy') }}">Privacy</a>
                <a href="mailto:{{ $site['email'] }}">Email</a>
            </div>
        </nav>
    </div>
</footer>
