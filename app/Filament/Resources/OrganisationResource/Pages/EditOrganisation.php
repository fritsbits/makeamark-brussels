<?php

namespace App\Filament\Resources\OrganisationResource\Pages;

use App\Filament\Resources\OrganisationResource;
use App\Filament\Support\EditApplication;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class EditOrganisation extends EditApplication
{
    protected static string $resource = OrganisationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('review')->label('My review')->icon('heroicon-o-star')
                ->fillForm(fn (): array => $this->getRecord()->reviews()->where('user_id', auth()->id())->first()?->only(['rating', 'comment']) ?? [])
                ->schema([
                    Select::make('rating')->label('My rating (1–5)')->options([1 => '1 · Low fit', 2 => '2', 3 => '3', 4 => '4', 5 => '5 · Strong fit'])->required()->native(false),
                    Textarea::make('comment')->label('My assessment')->rows(5)->maxLength(10000),
                ])
                ->action(function (array $data): void {
                    $this->authorize('update', $this->getRecord());
                    $this->getRecord()->reviews()->updateOrCreate(
                        ['user_id' => auth()->id()],
                        ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null],
                    );
                    $this->record = OrganisationResource::getEloquentQuery()->findOrFail($this->getRecord()->id);
                    Notification::make()->title('Your review is saved')->success()->send();
                }),
        ];
    }
}
