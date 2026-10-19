<?php

namespace App\Filament\Support;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

abstract class EditApplication extends EditRecord
{
    public function getTitle(): string
    {
        return $this->getRecord()->display_name;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $fields = $record->audience === 'organisations'
            ? ['status', 'refined_brief', 'skills_needed', 'internal_notes']
            : ['status', 'main_skills', 'backup_skills', 'internal_notes'];

        try {
            $this->record = DB::transaction(function () use ($record, $data, $fields): Model {
                $fresh = $record->newQuery()->whereKey($record->id)->lockForUpdate()->firstOrFail();
                $this->authorize('update', $fresh);
                $fresh->update(Arr::only($data, $fields));

                return $fresh;
            }, 3);
        } catch (ValidationException $exception) {
            $errors = [];
            foreach ($exception->errors() as $field => $messages) {
                $errors['data.'.$field] = $messages;
            }

            throw ValidationException::withMessages($errors);
        }

        return $this->record;
    }
}
