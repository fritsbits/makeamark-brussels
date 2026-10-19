@props(['file', 'widths' => [320], 'defaultWidth' => null, 'treatment' => null])
@php
    if (!preg_match('/^[a-z\d][a-z\d_-]*\.(jpe?g|png|webp)$/i', $file)) {
        throw new InvalidArgumentException('Photo filenames must be simple image basenames.');
    }
    if ($treatment !== null && !in_array($treatment, ['riso', 'monochrome'], true)) {
        throw new InvalidArgumentException('Unknown photo treatment.');
    }
    $name = pathinfo($file, PATHINFO_FILENAME);
    $defaultWidth ??= $widths[0];
    $prefix = $treatment === 'riso' ? '/images/treatments/riso/' : '/images/';
    $imageUrl = function ($width) use ($name, $prefix, $treatment) {
        $url = $prefix.$name.'.'.$width.'.webp';
        $path = public_path($url);
        return $treatment === 'riso' && is_file($path) ? $url.'?v='.substr(hash_file('sha256', $path), 0, 12) : $url;
    };
    $srcset = implode(', ', array_map(fn ($width) => $imageUrl($width).' '.$width.'w', $widths));
@endphp
<img src="{{ $imageUrl($defaultWidth) }}" srcset="{{ $srcset }}" @if($treatment) data-photo-treatment="{{ $treatment }}" @endif {{ $attributes->class(['photo--monochrome' => $treatment === 'monochrome']) }}>
