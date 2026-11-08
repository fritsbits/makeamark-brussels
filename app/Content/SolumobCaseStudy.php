<?php

namespace App\Content;

class SolumobCaseStudy
{
    public function isPublished(): bool
    {
        return config('case-studies.solumob.published', false) === true;
    }

    public function isVisible(): bool
    {
        return $this->isPublished() || app()->environment('local', 'testing');
    }

    public function mediaPath(string $image): ?string
    {
        $originals = ['website-before', 'website-after', 'facebook-cover', 'volunteer-story', 'beneficiary-story', 'recruitment-post', 'hero'];
        if (in_array($image, $originals, true)) {
            return resource_path('images/case-studies/solumob/'.$image.'.jpg');
        }

        $frames = [
            'output-map' => '0209-output-map.jpg',
            'photoshoot' => '0138-photoshoot.jpg',
            'editing' => '0132-editing.jpg',
        ];

        return isset($frames[$image])
            ? base_path('docs/raw/assets/solumob-2019/'.$frames[$image])
            : null;
    }
}
