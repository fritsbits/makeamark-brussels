<?php

namespace App\Content;

class EditionStatus
{
    /** @return array{label: string, href: ?string} */
    public static function pill(string $status, int $year, ?string $applyUrl, string $instagramUrl, array $labels): array
    {
        [$key, $href] = match (true) {
            $status === 'open' && filled($applyUrl) => ['open', $applyUrl],
            $status === 'selected' => ['selected', null],
            $status === 'done' => ['done', $instagramUrl],
            $status === 'preparing' => ['preparing', null],
            default => ['closed', null],
        };

        return ['label' => str_replace('{year}', (string) $year, $labels[$key]), 'href' => $href];
    }
}
