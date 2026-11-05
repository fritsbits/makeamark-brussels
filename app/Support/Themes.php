<?php

namespace App\Support;

/** The ten social themes Makers and organisations both choose from. */
class Themes
{
    /** @return list<string> */
    public static function all(): array
    {
        return [
            'Poverty reduction and social security',
            'Culture and community building',
            'Health and well-being',
            'Housing and living conditions',
            'Local economy and employment',
            'Environment and sustainability',
            'Education and learning',
            'Social inclusion and diversity',
            'Sports and recreation',
            'Refugee and migrant support',
        ];
    }

    /** Label keyed by label, for admin selects; themes are stored by their label. */
    public static function options(): array
    {
        return array_combine(self::all(), self::all());
    }

    /** Keep only known themes, once each, in catalogue order. */
    public static function normalise(mixed $themes): array
    {
        return is_array($themes) ? array_values(array_intersect(self::all(), array_filter($themes, 'is_string'))) : [];
    }
}
