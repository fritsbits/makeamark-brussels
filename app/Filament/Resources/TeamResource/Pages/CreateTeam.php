<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use App\Filament\Support\SavesTeam;
use App\Models\Team;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTeam extends CreateRecord
{
    use SavesTeam;

    protected static string $resource = TeamResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return $this->persistTeam(new Team, $data);
    }

    protected function getRedirectUrl(): string
    {
        return TeamResource::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
