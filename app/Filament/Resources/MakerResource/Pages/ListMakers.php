<?php

namespace App\Filament\Resources\MakerResource\Pages;

use App\Filament\Resources\MakerResource;
use Filament\Resources\Pages\ListRecords;

class ListMakers extends ListRecords
{
    protected static string $resource = MakerResource::class;

    public function getSubheading(): ?string
    {
        return 'Select makers, review their skills and find who still needs a team.';
    }
}
