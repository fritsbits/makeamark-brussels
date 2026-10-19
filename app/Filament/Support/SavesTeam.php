<?php

namespace App\Filament\Support;

use App\Models\Team;
use App\Services\Matchmaking;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

trait SavesTeam
{
    protected function persistTeam(Team $team, array $data): Team
    {
        $makerIds = $data['maker_ids'] ?? [];
        unset($data['maker_ids'], $data['candidate_skill'], $data['candidate_language']);

        try {
            return app(Matchmaking::class)->save($team, $data, $makerIds);
        } catch (ValidationException $exception) {
            if (isset($exception->errors()['lock_version'])) {
                Notification::make()->title('This team has changed')->body('Another organiser saved changes. Reload this page before saving your edits.')->danger()->persistent()->send();
            }
            $errors = [];
            foreach ($exception->errors() as $field => $messages) {
                $errors['data.'.$field] = $messages;
            }

            throw ValidationException::withMessages($errors);
        }
    }
}
