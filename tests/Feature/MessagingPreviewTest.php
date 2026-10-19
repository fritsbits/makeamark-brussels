<?php

namespace Tests\Feature;

use Tests\TestCase;

class MessagingPreviewTest extends TestCase
{
    public function test_selected_home_has_no_process_and_original_is_preserved(): void
    {
        $this->withoutVite()->get('/tools/messaging')->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Volunteer makers and Brussels social organisations team up')
            ->assertDontSee('How it works')
            ->assertSee('Venue to be confirmed')
            ->assertSee('href="/makers"', false)
            ->assertSee('href="/organisations"', false)
            ->assertSee('Watch the BRUZZ report from the 2023 edition')
            ->assertSee('Organise in your city')
            ->assertDontSee('application details are still being prepared')
            ->assertDontSee('Young people are part of the 2027 edition')
            ->assertDontSee('Who will you be making a mark with?')
            ->assertDontSee('strip__list');

        $this->get('/tools/messaging/original')->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Young people are part of the 2027 edition')
            ->assertSee('Who will you be making a mark with?')
            ->assertSee('application details are still being prepared');
    }

    public function test_preview_audience_pages_keep_preparation_and_selection_visible(): void
    {
        $this->withoutVite()->get('/tools/messaging/makers')->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('What you commit to')
            ->assertSee('planning meeting beforehand')
            ->assertSee('Contribute a full day pro-bono')
            ->assertSee('Applying does not guarantee selection.')
            ->assertSee('href="#application"', false)
            ->assertDontSee('Bring a full day, and your point of view.');

        $this->get('/tools/messaging/organisations')->assertOk()
            ->assertSee('Prepare your brief and materials')
            ->assertSee('feedback and the evening presentation')
            ->assertSee('Taking part is free.')
            ->assertSee('href="#application"', false);
    }

    public function test_comparison_shows_both_live_pages_without_joining_the_public_sitemap(): void
    {
        $this->withoutVite()->get('/tools/messaging/compare')->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('title="Original homepage"', false)
            ->assertSee('title="Distilled homepage"', false)
            ->assertSee('src="/"', false)
            ->assertSee('src="/tools/messaging/original"', false);

        $this->get('/sitemap-0.xml')->assertOk()->assertDontSee('/tools/messaging');
    }

    public function test_preview_routes_are_unavailable_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        foreach (['/tools/messaging', '/tools/messaging/original', '/tools/messaging/compare', '/tools/messaging/makers', '/tools/messaging/organisations'] as $path) {
            $this->get($path)->assertNotFound();
        }

        $this->withoutVite()->get('/')->assertOk()->assertDontSee('How it works')->assertDontSee('/tools/messaging');
        $this->get('/makers')->assertOk()->assertSee('What you commit to');
    }
}
