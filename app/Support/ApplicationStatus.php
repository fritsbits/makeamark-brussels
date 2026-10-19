<?php

namespace App\Support;

class ApplicationStatus
{
    public static function options(): array
    {
        return [
            'applied' => 'Applied',
            'needs_information' => 'Needs information',
            'contacted' => 'Contacted',
            'selected' => 'Selected',
            'confirmed' => 'Confirmed',
            'waitlisted' => 'Waitlisted',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn',
        ];
    }

    public static function color(string $status): string
    {
        return match ($status) {
            'selected', 'confirmed' => 'success',
            'needs_information', 'waitlisted' => 'warning',
            'rejected', 'withdrawn' => 'gray',
            default => 'info',
        };
    }
}
