<?php

namespace Tests\Feature;

use Tests\TestCase;

class MakerFormComponentsTest extends TestCase
{
    public function test_fields_and_choices_render_as_flux_controls(): void
    {
        $document = new \DOMDocument;
        @$document->loadHTML($this->withoutVite()->get('/makers')->assertOk()->getContent());
        $xpath = new \DOMXPath($document);

        $this->assertCount(12, $xpath->query('//ui-checkbox[@data-flux-checkbox]'));
        $this->assertCount(7, $xpath->query('//textarea[@data-flux-textarea]'));
        $this->assertCount(1, $xpath->query('//button[@data-flux-button and @type="submit"]'));
        $this->assertCount(0, $xpath->query('//*[name()="flux:checkbox"]'));
    }

    public function test_language_levels_use_flux_native_selects_with_all_choices(): void
    {
        $html = $this->withoutVite()->get('/makers')->assertOk()->getContent();
        $document = new \DOMDocument;
        @$document->loadHTML($html);
        $xpath = new \DOMXPath($document);

        foreach (['dutch', 'english', 'french'] as $language) {
            $selects = $xpath->query('//select[@name="'.$language.'" and @data-flux-select-native]');
            $this->assertCount(1, $selects, 'Language levels must use Flux native selects.');
            $this->assertCount(6, $xpath->query('option', $selects->item(0)));
        }
    }
}
