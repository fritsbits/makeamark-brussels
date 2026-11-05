<?php

namespace App\Support;

use App\Models\Application;
use Illuminate\Support\Str;

/** Builds the data for the two print views; the briefing never carries contact details. */
class ApplicationPrint
{
    /** Briefing for Makers: organisation name, refined brief, skills, themes and languages only. */
    public static function briefing(Application $organisation): array
    {
        $answers = $organisation->answers ?? [];
        $languages = $answers['organisation_languages'] ?? $answers['languages'] ?? [];

        return [
            'name' => $organisation->display_name,
            'edition' => $organisation->edition_year,
            'brief' => $organisation->refined_brief,
            'skills' => array_map(fn (string $skill): string => Skills::options()[$skill] ?? $skill, $organisation->skills_needed ?? []),
            'themes' => self::list($answers['themes'] ?? []),
            'languages' => self::list($languages),
        ];
    }

    /** The organisation's own copy of what it submitted, as label and value rows. */
    public static function answers(Application $application): array
    {
        $checks = ['youth_brussels_eligibility', 'assets_ready', 'planning_meeting', 'feedback_session', 'evening_presentation'];
        $labels = [
            'organisation_name' => 'Organisation name',
            'youth_brussels_eligibility' => 'Works with young people in Brussels',
            'mission' => 'Mission',
            'website' => 'Website',
            'social_media' => 'Social media',
            'challenge' => 'Challenge',
            'themes' => 'Themes',
            'organisation_languages' => 'Organisation languages',
            'contact_name' => 'Contact person',
            'email' => 'Email',
            'phone' => 'Phone',
            'languages' => 'Contact person languages',
            'assets_ready' => 'Briefing and materials ready',
            'planning_meeting' => 'Can join a planning meeting',
            'feedback_session' => 'Can join the feedback session',
            'evening_presentation' => 'Can join the evening presentation',
        ];
        $rows = [];
        $answers = $application->answers ?? [];

        // Follow the form's order; unknown keys come last.
        foreach (array_merge(array_intersect_key($labels, $answers), $answers) as $key => $_) {
            $value = $answers[$key];
            $value = is_array($value) ? implode(', ', self::list($value)) : (is_scalar($value) ? (string) $value : '');

            if (in_array($key, $checks, true)) {
                $value = in_array($value, ['1', 'true', 'yes'], true) ? 'Confirmed' : 'Not confirmed';
            }

            $rows[] = ['label' => $labels[$key] ?? Str::headline($key), 'value' => $value !== '' ? $value : 'Not supplied'];
        }

        return $rows;
    }

    private static function list(mixed $values): array
    {
        return is_array($values) ? array_values(array_filter($values, 'is_string')) : [];
    }
}
