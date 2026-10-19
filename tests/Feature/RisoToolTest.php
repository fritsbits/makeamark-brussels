<?php

namespace Tests\Feature;

use Tests\TestCase;

class RisoToolTest extends TestCase
{
    public function test_internal_tool_and_sample_are_available_locally_but_excluded_from_search(): void
    {
        $this->withoutVite()->get('/tools/riso')->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Download PNG');
        $this->get('/tools/riso/sample')->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->get('/sitemap-0.xml')->assertDontSee('/tools/riso');
    }

    public function test_internal_routes_also_check_the_runtime_environment(): void
    {
        $this->app['env'] = 'production';
        $this->withoutVite()->get('/tools/riso')->assertNotFound();
        $this->get('/tools/riso/sample')->assertNotFound();
    }
}
