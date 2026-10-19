<?php

namespace App\Content;

use Illuminate\Support\Str;
use RuntimeException;

class Homepage
{
    public function data(): array
    {
        $site = $this->read('site.json');
        $editions = collect(glob(resource_path('content/editions/*.json')))
            ->map(fn ($file) => $this->read('editions/'.basename($file)))
            ->sortByDesc('year');
        $edition = $editions->first() ?? throw new RuntimeException('No edition found in resources/content/editions.');
        $edition['html'] = $this->markdown($edition['body']);
        $photo = pathinfo($edition['photo'], PATHINFO_FILENAME);
        $edition['image'] = '/images/'.$photo.'.440.webp';
        $edition['imageLarge'] = '/images/'.$photo.'.880.webp';
        [$width, $height] = getimagesize(public_path($edition['image']));
        $edition['photoHeight'] = (int) round(440 * $height / $width);
        $edition['pill'] = EditionStatus::pill($edition['status'], $edition['year'], $edition['applyUrl'] ?? null, $site['instagramUrl'], $site['edition']['statusLabels']);

        $faq = collect(glob(resource_path('content/faq/*.json')))
            ->map(fn ($file) => $this->read('faq/'.basename($file)))
            ->sortBy('order')
            ->map(fn ($entry) => [...$entry, 'html' => $this->markdown($entry['body'])]);

        return [
            'site' => $site,
            'edition' => $edition,
            'faq' => $faq,
            'stories' => $this->read('stories.json'),
            'organisations' => collect($this->read('organisations.json'))->sortBy(fn ($org) => Str::lower(Str::ascii($org['name']))),
        ];
    }

    public function site(): array
    {
        return $this->read('site.json');
    }

    private function read(string $file): array
    {
        return json_decode(file_get_contents(resource_path('content/'.$file)), true, flags: JSON_THROW_ON_ERROR);
    }

    private function markdown(string $text): string
    {
        return Str::markdown($text, ['html_input' => 'strip', 'allow_unsafe_links' => false]);
    }
}
