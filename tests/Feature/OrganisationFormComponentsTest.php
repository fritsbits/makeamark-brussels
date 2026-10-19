<?php

namespace Tests\Feature;

use DOMDocument;
use DOMXPath;
use Tests\TestCase;

class OrganisationFormComponentsTest extends TestCase
{
    public function test_organisation_fields_keep_their_contract_using_flux_controls(): void
    {
        $xpath = $this->form();

        foreach ([
            'organisation_name' => ['input', 'data-flux-control', true, 255],
            'mission' => ['textarea', 'data-flux-textarea', true, 5000],
            'website' => ['input', 'data-flux-control', false, 2000],
            'social_media' => ['textarea', 'data-flux-textarea', false, 2000],
            'challenge' => ['textarea', 'data-flux-textarea', true, 5000],
            'contact_name' => ['input', 'data-flux-control', true, 255],
            'email' => ['input', 'data-flux-control', true, 255],
            'phone' => ['input', 'data-flux-control', true, 80],
        ] as $name => [$tag, $marker, $required, $maximum]) {
            $controls = $xpath->query('//'.$tag.'[@name="'.$name.'" and @'.$marker.']');
            $this->assertCount(1, $controls, $name.' must use a Flux control.');
            $control = $controls->item(0);
            $this->assertSame($required, $control->hasAttribute('required'), $name.' requiredness changed.');
            $this->assertSame((string) $maximum, $control->getAttribute('maxlength'));
        }

        foreach (['youth_brussels_eligibility', 'assets_ready', 'planning_meeting', 'feedback_session', 'evening_presentation'] as $name) {
            $this->assertCount(1, $xpath->query('//ui-checkbox[@name="'.$name.'" and @required and @data-flux-checkbox]'));
        }

        $this->assertCount(1, $xpath->query('//button[@data-flux-button and @type="submit"]'));
    }

    public function test_languages_remain_a_choice_group_with_no_individually_required_language(): void
    {
        $xpath = $this->form();
        $this->assertCount(1, $xpath->query('//*[@id="languages" and @aria-labelledby="languages-label"]'));
        $this->assertCount(3, $xpath->query('//ui-checkbox[@name="languages[]" and not(@required)]'));
        foreach (['English', 'Dutch', 'French'] as $language) {
            $this->assertCount(1, $xpath->query('//ui-checkbox[@name="languages[]" and @value="'.$language.'"]'));
        }
    }

    private function form(): DOMXPath
    {
        $document = new DOMDocument;
        @$document->loadHTML($this->withoutVite()->get('/organisations')->assertOk()->getContent());

        return new DOMXPath($document);
    }
}
