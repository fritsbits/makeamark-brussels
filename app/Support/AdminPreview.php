<?php

namespace App\Support;

use Filament\Facades\Filament;

/** Logged-in admins can see and send a form while it is still closed to the public. */
final class AdminPreview
{
    public static function active(Audience $definition, array $edition): bool
    {
        return ! $definition->isOpen($edition) && self::allowed();
    }

    public static function allowed(): bool
    {
        return auth()->user()?->canAccessPanel(Filament::getPanel('admin')) ?? false;
    }
}
