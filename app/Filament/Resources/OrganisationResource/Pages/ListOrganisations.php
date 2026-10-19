<?php

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use Filament\Resources\Pages\ListRecords;

class ListOrganisations extends ListRecords
{
    protected static string $resource = OrganisationResource::class;

    public function getSubheading(): ?string
    {
        return 'Review separately, compare assessments, then refine the briefing and skills needed.';
    }
}
