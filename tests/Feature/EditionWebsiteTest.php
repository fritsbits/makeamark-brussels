<?php

namespace Tests\Feature;

use Tests\TestCase;

class EditionWebsiteTest extends TestCase
{
    public function test_homepage_introduces_the_current_edition_and_both_applicant_journeys(): void
    {
        $response = $this->withoutVite()->get('/');

        $response->assertOk()
            ->assertSee('Make a Mark Brussels')
            ->assertSee('27 March 2027')
            ->assertSee('For Makers')
            ->assertSee('Social organisations,')
            ->assertSee('organisations that support young people in Brussels')
            ->assertSee('href="/makers"', false)
            ->assertSee('href="/organisations"', false)
            ->assertDontSee('How it works')
            ->assertDontSee('application details are still being prepared')
            ->assertDontSee('/tools/messaging');
        $response->assertSee('aria-label="Apply now. Explore ways to take part."', false);
        $this->assertNull($response->headers->get('X-Robots-Tag'));

        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
    }

    public function test_audience_pages_explain_each_groups_commitment_and_include_their_application(): void
    {
        $this->withoutVite()->get('/makers')->assertOk()
            ->assertSee('Use your creative skills to make a difference.')
            ->assertSee('What you commit to')
            ->assertSee('planning meeting beforehand')
            ->assertSee('Contribute a full day pro-bono')
            ->assertSee('href="/#about"', false)
            ->assertSee('href="#application"', false)
            ->assertSee('name="portfolio"', false)
            ->assertSee('Send Maker application')
            ->assertDontSee('Application details will be published here when they are ready');

        $this->withoutVite()->get('/organisations')->assertOk()
            ->assertSee('Get a Maker team to bring a long-waiting project to life.')
            ->assertSee('Taking part is free.')
            ->assertSee('Prepare your brief and materials')
            ->assertSee('href="#application"', false)
            ->assertSee('name="challenge"', false)
            ->assertSee('Send organisation application')
            ->assertDontSee('Application details will be published here when they are ready');
    }

    public function test_the_application_deadline_renders_only_when_the_edition_sets_one(): void
    {
        $withDeadline = ['deadline' => new \DateTimeImmutable('2027-01-31')];

        $this->blade('<x-application-deadline :edition="$edition" />', ['edition' => $withDeadline])
            ->assertSee('Apply by')
            ->assertSee('<time datetime="2027-01-31">31 January 2027</time>', false);
        $this->blade('<x-application-deadline :edition="$edition" />', ['edition' => ['deadline' => null]])->assertDontSee('Apply by');

        foreach (['/', '/makers', '/organisations'] as $path) {
            $this->withoutVite()->get($path)->assertOk()->assertDontSee('Apply by');
        }
    }

    public function test_the_maker_date_confirmation_uses_the_edition_dates(): void
    {
        $this->withoutVite()->get('/makers')->assertOk()->assertSee('I have Saturday 27 March 2027 in my calendar.');
    }

    public function test_maker_is_capitalised_in_rendered_public_pages(): void
    {
        foreach (['/', '/makers', '/organisations', '/privacy'] as $path) {
            $html = $this->withoutVite()->get($path)->assertOk()->getContent();
            $html = preg_replace('#<(script|style)\b.*?</\1>#is', '', $html);
            preg_match_all('/\b(?:alt|aria-label|title|content|placeholder)="([^"]*)"/i', $html, $attributes);
            $visible = preg_replace('#https?://\S+#', '', html_entity_decode(strip_tags($html).' '.implode(' ', $attributes[1])));

            preg_match('/.{0,40}\bmakers?\b.{0,40}/s', $visible, $found);

            $this->assertSame([], $found, "Lowercase maker word on {$path}");
        }
    }

    public function test_old_application_links_take_applicants_to_the_embedded_form(): void
    {
        $this->get('/makers/apply')->assertRedirect('/makers#application');

        $this->get('/organisations/apply')->assertRedirect('/organisations#application');
    }

    public function test_search_engine_endpoints_list_the_public_edition_pages(): void
    {
        $this->get('/sitemap-0.xml')->assertOk()
            ->assertSee('https://letsmakeamark.brussels/makers', false)
            ->assertSee('https://letsmakeamark.brussels/organisations', false);
    }
}
