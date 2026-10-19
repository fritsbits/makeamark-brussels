<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PhotoTreatmentTest extends TestCase
{
    public function test_all_homepage_photos_share_monochrome_treatment_and_original_reveal(): void
    {
        $html = $this->withoutVite()->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('/images/edition-2026-group.440.webp', $html);
        $this->assertStringContainsString('/images/edition-2026-group.880.webp', $html);
        $this->assertStringNotContainsString('/images/treatments/riso/', $html);
        $this->assertSame(13, substr_count($html, 'data-photo-treatment="monochrome"'));
        $this->assertSame(13, substr_count($html, 'class="photo-reveal__original"'));
        $this->assertStringContainsString('Return to desaturated photo:', $html);
        $this->assertStringNotContainsString('polaroid__photo duo', $html);
        $this->assertStringNotContainsString('/images/story-3.320.webp', $html);
        $this->assertStringNotContainsString('/images/treatments/riso/story-', $html);
    }

    public function test_photo_component_keeps_the_original_when_no_treatment_is_selected(): void
    {
        $html = Blade::render('<x-photo file="story-3.jpg" :widths="[240, 320, 640]" :default-width="320" alt="Original photo" width="320" height="569" loading="lazy" />');
        $this->assertStringContainsString('src="/images/story-3.320.webp"', $html);
        $this->assertStringContainsString('/images/story-3.240.webp 240w', $html);
        $this->assertStringNotContainsString('/treatments/', $html);
        $this->assertStringContainsString('alt="Original photo"', $html);
        $this->assertStringContainsString('loading="lazy"', $html);
    }

    public function test_the_same_component_can_opt_another_photo_into_riso(): void
    {
        $html = Blade::render('<x-photo file="story-3.jpg" :widths="[320, 640]" treatment="riso" alt="Selected photo" />');
        $this->assertStringContainsString('/images/treatments/riso/story-3.320.webp', $html);
        $this->assertStringContainsString('/images/treatments/riso/story-3.640.webp', $html);
        $this->assertStringContainsString('alt="Selected photo"', $html);
    }
}
