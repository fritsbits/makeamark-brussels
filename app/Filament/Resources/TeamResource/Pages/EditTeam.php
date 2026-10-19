<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use App\Filament\Support\SavesTeam;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTeam extends EditRecord
{
    use SavesTeam;

    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->modalDescription('Remove this team and its assignments. Applications and reviews are kept.')];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['maker_ids'] = $this->getRecord()->makers()->pluck('applications.id')->all();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $this->record = $this->persistTeam($record, $data);
        $this->data['lock_version'] = $this->record->lock_version;

        return $this->record;
    }
}
