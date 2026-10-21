<?php

namespace Tests;

use App\Content\EditionStatus;
use App\Content\Homepage;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tests describe each edition state explicitly instead of following the live content file.
        $this->withEditionStatus('open');
    }

    protected function withEditionStatus(string $status): void
    {
        $this->app->instance(Homepage::class, new class($status) extends Homepage
        {
            public function __construct(private string $status) {}

            public function data(): array
            {
                $data = parent::data();
                $edition = [...$data['edition'], 'status' => $this->status];
                $edition['pill'] = EditionStatus::pill($this->status, $edition['year'], $edition['applyUrl'] ?? null, $data['site']['instagramUrl'], $data['site']['edition']['statusLabels']);

                return [...$data, 'edition' => $edition];
            }
        });
    }
}
