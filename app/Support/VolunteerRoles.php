<?php

namespace App\Support;

/** The event roles volunteers apply for and are assigned to. Stored by key. */
class VolunteerRoles
{
    /** @return array<string, string> */
    public static function options(): array
    {
        return [
            'photographer' => 'Photographer',
            'videographer' => 'Videographer',
            'social_media' => 'Social media manager',
        ];
    }

    /** @return array<string, string> */
    public static function dayParts(): array
    {
        return [
            'daytime' => 'Daytime, while the teams work',
            'evening' => 'Evening, for the presentations',
        ];
    }

    /** Keep only known roles, once each, in catalogue order. */
    public static function normalise(mixed $roles): array
    {
        return is_array($roles) ? array_values(array_intersect(array_keys(self::options()), array_filter($roles, 'is_string'))) : [];
    }
}
