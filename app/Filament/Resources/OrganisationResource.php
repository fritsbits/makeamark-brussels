<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganisationResource\Pages;
use App\Filament\Support\ApplicationResource;
use BackedEnum;

class OrganisationResource extends ApplicationResource
{
    protected static string $audience = 'organisations';

    protected static ?string $slug = 'organisations';

    protected static ?string $modelLabel = 'organisation';

    protected static ?string $pluralModelLabel = 'Organisations';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 1;

    public static function getPages(): array
    {
        return ['index' => Pages\ListOrganisations::route('/'), 'edit' => Pages\EditOrganisation::route('/{record}/edit')];
    }
}
