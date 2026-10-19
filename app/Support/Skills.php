<?php

namespace App\Support;

use Illuminate\Support\Str;

class Skills
{
    public static function options(): array
    {
        return [
            'animation' => 'Animation',
            'art-direction' => 'Art direction',
            'brand-identity' => 'Brand identity',
            'brand-strategy' => 'Brand strategy',
            'campaign-strategy' => 'Campaign strategy',
            'content-development-copywriting' => 'Content development / copywriting',
            'digital-strategy' => 'Digital strategy',
            'graphic-design' => 'Graphic design',
            'illustrations' => 'Illustrations',
            'photography' => 'Photography',
            'pr' => 'PR',
            'print-production' => 'Print production',
            'service-design' => 'Service design',
            'social-media-design' => 'Social media design',
            'ux-design' => 'UX design',
            'video-production' => 'Video production',
            'web-design' => 'Web design',
            'back-end-development' => 'Back end development',
            'front-end-development' => 'Front end development',
        ];
    }

    public static function normalise(array|string|null $skills): array
    {
        $aliases = [
            'art-director' => 'art-direction',
            'copywriting' => 'content-development-copywriting',
            'content-development' => 'content-development-copywriting',
            'front-end-web-development' => 'front-end-development',
            'frontend-development' => 'front-end-development',
            'front-end' => 'front-end-development',
            'back-end-web-development' => 'back-end-development',
            'backend-development' => 'back-end-development',
            'back-end' => 'back-end-development',
            'ux' => 'ux-design',
            'user-experience' => 'ux-design',
            'user-experience-design' => 'ux-design',
            'illustration' => 'illustrations',
            'public-relations' => 'pr',
        ];
        $normalised = [];
        foreach (is_array($skills) ? $skills : [$skills] as $value) {
            if (! is_string($value)) {
                continue;
            }
            foreach (preg_split('/[,;\r\n]+/', $value) as $label) {
                $slug = Str::slug(str_replace(['/', '_'], ' ', trim($label)));
                $slug = $aliases[$slug] ?? $slug;
                if (array_key_exists($slug, self::options())) {
                    $normalised[] = $slug;
                }
            }
        }

        return array_values(array_unique($normalised));
    }
}
