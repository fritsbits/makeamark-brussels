<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MakerResource\Pages;
use App\Filament\Support\ApplicationResource;
use BackedEnum;

class MakerResource extends ApplicationResource
{
    protected static string $audience = 'makers';

    protected static ?string $slug = 'makers';

    protected static ?string $modelLabel = 'maker';

    protected static ?string $pluralModelLabel = 'Makers';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?int $navigationSort = 2;

    public static function getPages(): array
    {
        return ['index' => Pages\ListMakers::route('/'), 'edit' => Pages\EditMaker::route('/{record}/edit')];
    }
}
