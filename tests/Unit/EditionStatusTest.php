<?php

namespace Tests\Unit;

use App\Content\EditionStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EditionStatusTest extends TestCase
{
    #[DataProvider('statuses')]
    public function test_authored_status_controls_the_action(string $status, ?string $applyUrl, string $label, ?string $href): void
    {
        $pill = EditionStatus::pill($status, 2026, $applyUrl, 'https://instagram.com/example', [
            'open' => 'Apply {year}',
            'closed' => 'Closed {year}',
            'selected' => 'Selected {year}',
            'done' => 'Read {year} / {year}',
            'preparing' => 'Preparing {year}',
        ]);

        $this->assertSame(['label' => $label, 'href' => $href], $pill);
    }

    public static function statuses(): array
    {
        return [
            ['open', 'https://example.com/apply', 'Apply 2026', 'https://example.com/apply'],
            ['open', null, 'Closed 2026', null],
            ['closed', 'https://example.com/apply', 'Closed 2026', null],
            ['selected', null, 'Selected 2026', null],
            ['done', null, 'Read 2026 / 2026', 'https://instagram.com/example'],
            ['preparing', 'https://example.com/apply', 'Preparing 2026', null],
        ];
    }
}
