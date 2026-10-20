@props(['site', 'pageTitle' => null, 'pageDescription' => null, 'publicTypography' => false])
@php
    $pageTitle = $pageTitle ?? $site['meta']['title'];
    $pageDescription = $pageDescription ?? $site['meta']['description'];
    $canonical = rtrim($site['domain'], '/').request()->getPathInfo();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="sitemap" href="/sitemap-index.xml">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ rtrim($site['domain'], '/') }}/og-2027.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Make a Mark for Brussels youth. Saturday 27 March 2027. The ninth edition.">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preload" href="/fonts/a3481c02dc63831a.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/fonts/23310fc1bc6aca45.woff2" as="font" type="font/woff2" crossorigin>
    @if ($publicTypography)
        <link rel="preload" href="/fonts/plus-jakarta-sans-light-300.woff2" as="font" type="font/woff2" crossorigin>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if (app()->environment('production') && $publicTypography)
        <!-- Fathom - beautiful, simple website analytics -->
        <script src="https://cdn.usefathom.com/script.js" data-site="UDIIHURW" defer></script>
        <!-- / Fathom -->
    @endif
    @livewireStyles
</head>
<body @class(['public-site' => $publicTypography])>
    <a class="skip-link" href="#main">Skip to content</a>
    {{ $slot }}
    @livewireScripts
    @fluxScripts
</body>
</html>
