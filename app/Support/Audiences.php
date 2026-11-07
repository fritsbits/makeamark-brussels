<?php

namespace App\Support;

use Illuminate\Validation\Rule;
use InvalidArgumentException;

/** The applicant audiences, keyed by URL slug. Unknown slugs fail loudly instead of falling into another audience's branch. */
class Audiences
{
    /** @return array<string, Audience> */
    public static function all(): array
    {
        static $audiences;

        return $audiences ??= collect([self::makers(), self::organisations(), self::volunteers()])
            ->keyBy('slug')->all();
    }

    /** @return list<string> */
    public static function slugs(): array
    {
        return array_keys(self::all());
    }

    public static function find(string $slug): Audience
    {
        return self::all()[$slug] ?? throw new InvalidArgumentException("Unknown audience [{$slug}].");
    }

    private static function makers(): Audience
    {
        return new Audience(
            slug: 'makers',
            noun: 'Maker',
            nameField: 'name',
            pageTitle: 'For Makers',
            pageDescription: 'Learn what to expect when you volunteer as a Maker for the Make a Mark Brussels 2027 edition.',
            receiptThanks: 'Thanks for putting your hand up.',
            reviewFocus: 'your skills and availability',
            fields: [
                'name' => 255, 'email' => 255, 'phone' => 80, 'motivation' => 5000, 'referral' => 1000,
                'dutch' => 255, 'english' => 255, 'french' => 255, 'other_languages' => 1000,
                'portfolio' => 2000, 'social_media' => 2000, 'main_skills' => 3000,
                'backup_skills' => 3000, 'other_skills' => 3000, 'team_preferences' => 2000,
                'meeting_availability' => 2000,
            ],
            checkboxes: ['date_confirmed', 'full_commitment'],
            lists: ['themes' => 10],
            rules: fn (array $input): array => [
                'email' => ['required', 'email:rfc', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:80'],
                'motivation' => ['required', 'string', 'max:5000'],
                'referral' => ['nullable', 'string', 'max:1000'],
                'dutch' => ['nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'english' => ['nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'french' => ['nullable', Rule::in(['1', '2', '3', '4', '5'])],
                'other_languages' => ['nullable', 'string', 'max:1000'],
                'portfolio' => ['required', 'url', 'max:2000'],
                'social_media' => ['nullable', 'string', 'max:2000'],
                'main_skills' => ['required', 'string', 'max:3000'],
                'backup_skills' => ['nullable', 'string', 'max:3000'],
                'other_skills' => ['nullable', 'string', 'max:3000'],
                'team_preferences' => ['nullable', 'string', 'max:2000'],
                'themes' => ['sometimes', 'array', 'max:10'],
                'themes.*' => ['string', 'distinct', Rule::in(Themes::all())],
                'date_confirmed' => ['accepted'],
                'meeting_availability' => ['required', 'string', 'max:2000'],
                'full_commitment' => ['accepted'],
            ],
            messages: [],
            secretKey: 'maker_submission_secret',
            legacyTokens: true,
        );
    }

    private static function organisations(): Audience
    {
        return new Audience(
            slug: 'organisations',
            noun: 'organisation',
            nameField: 'contact_name',
            pageTitle: 'For social organisations',
            pageDescription: 'Learn how your Brussels social organisation can bring a communication, design, or digital challenge to Make a Mark.',
            receiptThanks: 'Thanks for bringing your challenge.',
            reviewFocus: 'your organisation and challenge',
            fields: [
                'organisation_name' => 255, 'email' => 255, 'mission' => 5000,
                'social_media' => 2000, 'website' => 2000, 'challenge' => 5000,
                'contact_name' => 255, 'phone' => 80,
            ],
            checkboxes: ['youth_brussels_eligibility', 'assets_ready', 'planning_meeting', 'feedback_session', 'evening_presentation'],
            lists: ['organisation_languages' => 3, 'languages' => 3, 'themes' => 10],
            rules: fn (array $input): array => [
                'email' => ['required', 'email:rfc', 'max:255'],
                'organisation_name' => ['required', 'string', 'max:255'],
                'youth_brussels_eligibility' => ['accepted'],
                'mission' => ['required', 'string', 'max:5000'],
                'social_media' => ['nullable', 'string', 'max:2000'],
                'website' => ['nullable', 'url', 'max:2000'],
                'challenge' => ['required', 'string', 'max:5000'],
                'contact_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:80'],
                'themes' => ['sometimes', 'array', 'max:10'],
                'themes.*' => ['string', 'distinct', Rule::in(Themes::all())],
                'organisation_languages' => ['required', 'array', 'min:1', 'max:3'],
                'organisation_languages.*' => ['string', 'distinct', Rule::in(['English', 'Dutch', 'French'])],
                'languages' => ['required', 'array', 'min:1', 'max:3'],
                'languages.*' => ['string', 'distinct', Rule::in(['English', 'Dutch', 'French'])],
                'assets_ready' => ['accepted'],
                'planning_meeting' => ['accepted'],
                'feedback_session' => ['accepted'],
                'evening_presentation' => ['accepted'],
            ],
            messages: [
                'organisation_languages.required' => 'Choose at least one language your organisation works in.',
                'languages.required' => 'Choose at least one language your contact person can work in.',
                'youth_brussels_eligibility.accepted' => 'Please confirm that your organisation works with young people in Brussels.',
            ],
            secretKey: 'organisation_submission_secret',
        );
    }

    private static function volunteers(): Audience
    {
        return new Audience(
            slug: 'volunteers',
            noun: 'volunteer',
            nameField: 'name',
            pageTitle: 'For volunteers',
            pageDescription: 'Photograph, film or run social media for the Make a Mark Brussels 2027 edition.',
            receiptThanks: 'Thanks for offering to capture the day.',
            reviewFocus: 'your work and availability',
            fields: ['name' => 255, 'email' => 255, 'phone' => 80, 'portfolio' => 2000, 'role_notes' => 2000],
            checkboxes: ['date_confirmed'],
            lists: ['roles' => 3, 'languages' => 3, 'day_parts' => 2],
            rules: function (array $input): array {
                // Languages only matter for posting on social media; drop them for the other roles.
                $posts = is_array($input['roles'] ?? null) && in_array('social_media', $input['roles'], true);

                return [
                    'email' => ['required', 'email:rfc', 'max:255'],
                    'name' => ['required', 'string', 'max:255'],
                    'phone' => ['required', 'string', 'max:80'],
                    'roles' => ['required', 'array', 'min:1', 'max:3'],
                    'roles.*' => ['string', 'distinct', Rule::in(array_keys(VolunteerRoles::options()))],
                    'portfolio' => ['required', 'url', 'max:2000'],
                    'role_notes' => ['nullable', 'string', 'max:2000'],
                    'languages' => [Rule::excludeIf(! $posts), 'required', 'array', 'min:1', 'max:3'],
                    'languages.*' => [Rule::excludeIf(! $posts), 'string', 'distinct', Rule::in(['English', 'Dutch', 'French'])],
                    'date_confirmed' => ['accepted'],
                    'day_parts' => ['required', 'array', 'min:1', 'max:2'],
                    'day_parts.*' => ['string', 'distinct', Rule::in(array_keys(VolunteerRoles::dayParts()))],
                ];
            },
            messages: [
                'roles.required' => 'Choose at least one role.',
                'languages.required' => 'Choose at least one language you can post in.',
                'day_parts.required' => 'Choose when you can be there.',
                'date_confirmed.accepted' => 'Please confirm you have the date in your calendar.',
            ],
            secretKey: 'volunteer_submission_secret',
            followsEditionStatus: false,
        );
    }
}
