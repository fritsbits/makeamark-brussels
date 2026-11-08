<?php

namespace Tests\Feature;

use Tests\TestCase;

class CaseStudyPreviewTest extends TestCase
{
    public function test_solumob_is_published_with_both_participation_journeys(): void
    {
        $this->withoutVite()->get('/case-studies/solumob')->assertOk()
            ->assertSeeText('One day. Four Makers. More than a slide deck.')
            ->assertDontSee('Local preview')
            ->assertDontSee('Review note:')
            ->assertDontSee('separate from the 56-second service film')
            ->assertSee('href="/organisations"', false)
            ->assertSee('href="/makers"', false)
            ->assertSee('M6fTa72AHuo')
            ->assertDontSee('src="https://www.youtube-nocookie.com', false)
            ->assertSee('https://letsmakeamark.brussels/case-studies/solumob', false)
            ->assertSee('property="og:type" content="article"', false)
            ->assertDontSee('What surprised me was how quickly');

        $this->get('/sitemap-0.xml')->assertSee('/case-studies/solumob');
        $this->get('/tools/case-studies/solumob')->assertRedirect('/case-studies/solumob');
    }

    public function test_only_selected_documentary_frames_can_be_previewed(): void
    {
        $this->get('/tools/case-studies/solumob/media/output-map')->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg')
            ->assertOk();
        $this->get('/tools/case-studies/solumob/media/application-selected-fields')->assertNotFound();
    }

    public function test_draft_and_its_media_are_unavailable_in_production(): void
    {
        config(['case-studies.solumob.published' => false]);
        $this->app->detectEnvironment(fn () => 'production');

        $this->get('/tools/case-studies/solumob')->assertNotFound();
        $this->get('/tools/case-studies/solumob/media/output-map')->assertNotFound();
        $this->get('/case-studies/solumob')->assertNotFound();
        $this->get('/case-studies/solumob/media/website-after')->assertNotFound();
        $this->withoutVite()->get('/')->assertDontSee('/case-studies/solumob');
        $this->get('/makers')->assertDontSee('/case-studies/solumob');
        $this->get('/organisations')->assertDontSee('/case-studies/solumob');
        $this->get('/sitemap-0.xml')->assertDontSee('/case-studies/solumob');
    }

    public function test_recommended_links_lead_to_one_shared_published_case(): void
    {
        $this->withoutVite()->get('/')->assertSee('See what we made for Solumob')
            ->assertSee('href="/case-studies/solumob"', false);
        $this->get('/organisations')->assertSee('What can a day make?')
            ->assertSee('href="/case-studies/solumob"', false);
        $this->get('/makers')->assertSee('Get a feel for the creative sprint.')
            ->assertSee('href="/case-studies/solumob"', false);
    }

    public function test_volunteers_page_carries_no_case_study_invitation(): void
    {
        $this->get('/volunteers')->assertOk()
            ->assertDontSee('/case-studies/solumob')
            ->assertDontSee('What can a day make?');
    }

    public function test_publication_switch_updates_page_media_links_and_sitemap_together(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config(['case-studies.solumob.published' => true]);

        $response = $this->withoutVite()->get('/case-studies/solumob')->assertOk()
            ->assertDontSee('Local preview')->assertDontSee('Review note:');
        $this->assertFalse($response->headers->has('X-Robots-Tag'));
        $this->get('/case-studies/solumob/media/website-after')->assertOk();
        $this->get('/')->assertSee('href="/case-studies/solumob"', false);
        $this->get('/makers')->assertSee('href="/case-studies/solumob"', false);
        $this->get('/organisations')->assertSee('href="/case-studies/solumob"', false);
        $this->get('/sitemap-0.xml')->assertSee('https://letsmakeamark.brussels/case-studies/solumob');
    }

    public function test_original_presentation_media_use_only_the_selected_aliases(): void
    {
        foreach (['website-before', 'website-after', 'facebook-cover', 'volunteer-story', 'beneficiary-story', 'recruitment-post', 'hero'] as $image) {
            $this->get('/case-studies/solumob/media/'.$image)->assertOk()
                ->assertHeader('Content-Type', 'image/jpeg');
        }
        $this->get('/case-studies/solumob/media/presentation')->assertNotFound();
    }
}
