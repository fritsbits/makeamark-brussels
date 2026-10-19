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
            ->assertSee('For makers')
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
            ->assertSee('Send maker application')
            ->assertDontSee('Application details will be published here when they are ready');

        $this->withoutVite()->get('/organisations')->assertOk()
            ->assertSee('Get a maker team to bring a long-waiting project to life.')
            ->assertSee('Taking part is free.')
            ->assertSee('Prepare your brief and materials')
            ->assertSee('href="#application"', false)
            ->assertSee('name="challenge"', false)
            ->assertSee('Send organisation application')
            ->assertDontSee('Application details will be published here when they are ready');
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
