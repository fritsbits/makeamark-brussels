<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Team;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class Matchmaking
{
    public function save(Team $team, array $attributes, array $makerIds): Team
    {
        $validated = Validator::make([...$attributes, 'maker_ids' => $makerIds], [
            'maker_ids' => ['array'],
            'maker_ids.*' => ['required', 'integer'],
            'lock_version' => ['sometimes', 'integer', 'min:0'],
        ])->validate();
        $makerIds = array_map('intval', array_values($makerIds));
        if (count($makerIds) !== count(array_unique($makerIds))) {
            throw ValidationException::withMessages(['maker_ids' => 'Choose each maker only once.']);
        }

        try {
            return DB::transaction(function () use ($team, $attributes, $validated, $makerIds) {
                $record = $team->exists ? Team::whereKey($team->id)->lockForUpdate()->firstOrFail() : new Team;
                if ($record->exists && array_key_exists('lock_version', $validated)
                    && (int) $validated['lock_version'] !== $record->lock_version) {
                    throw ValidationException::withMessages(['lock_version' => 'This team changed while you were editing it. Reload and try again.']);
                }

                $fields = ['name', 'edition_year', 'organisation_application_id', 'status', 'notes'];
                $data = Validator::make(array_replace($record->only($fields), Arr::only($attributes, $fields)), [
                    'name' => ['required', 'string', 'max:255'],
                    'edition_year' => ['required', 'integer', 'between:2000,9999'],
                    'organisation_application_id' => ['required', 'integer'],
                    'status' => ['required', Rule::in(['draft', 'confirmed'])],
                    'notes' => ['nullable', 'string'],
                ])->validate();

                // Lock applications in a stable order before examining any memberships.
                $applications = Application::whereIn('id', array_unique([...$makerIds, (int) $data['organisation_application_id']]))
                    ->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $organisation = $applications->get($data['organisation_application_id']);
                if (! $this->eligible($organisation, 'organisations', (int) $data['edition_year'])) {
                    throw ValidationException::withMessages(['organisation_application_id' => 'Choose a selected or confirmed organisation from this edition.']);
                }
                if (Team::where('organisation_application_id', $organisation->id)->when($record->exists, fn ($query) => $query->whereKeyNot($record->id))->exists()) {
                    throw ValidationException::withMessages(['organisation_application_id' => 'This organisation already has a team.']);
                }
                foreach ($makerIds as $makerId) {
                    if (! $this->eligible($applications->get($makerId), 'makers', (int) $data['edition_year'])) {
                        throw ValidationException::withMessages(['maker_ids' => 'Choose selected or confirmed makers from this edition.']);
                    }
                }
                if (DB::table('application_team')->whereIn('application_id', $makerIds)
                    ->when($record->exists, fn ($query) => $query->where('team_id', '!=', $record->id))->exists()) {
                    throw ValidationException::withMessages(['maker_ids' => 'A selected maker already belongs to another team. Remove that assignment first.']);
                }

                $record->fill($data);
                $record->lock_version = $record->lock_version + 1;
                $record->save();
                $record->makers()->sync($makerIds);

                return $record->load(['organisation', 'makers']);
            }, 3);
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23000' || $exception->getCode() === '23505') {
                throw ValidationException::withMessages(['maker_ids' => 'An assignment changed while saving. Reload the team and try again.']);
            }
            throw $exception;
        }
    }

    private function eligible(?Application $application, string $audience, int $editionYear): bool
    {
        return $application && $application->audience === $audience
            && $application->edition_year === $editionYear
            && in_array($application->status, ['selected', 'confirmed'], true);
    }
}
