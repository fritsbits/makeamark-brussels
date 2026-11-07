<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VolunteerResource\Pages;
use App\Filament\Support\ApplicationResource;
use App\Models\Application;
use App\Support\ApplicationStatus;
use App\Support\VolunteerRoles;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/** Volunteers get roles, not teams, and are accepted by one organiser without ratings. */
class VolunteerResource extends ApplicationResource
{
    protected static string $audience = 'volunteers';

    protected static ?string $slug = 'volunteers';

    protected static ?string $modelLabel = 'Volunteer';

    protected static ?string $pluralModelLabel = 'Volunteers';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->addSelect(['applied_as_maker' => Application::query()->from('applications as makers')
            ->selectRaw('1')->where('makers.audience', 'makers')->whereRaw('lower(makers.email) = lower(applications.email)')->limit(1)]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Selection')
                ->description('Status changes save internally. They do not send emails.')
                ->columns(3)
                ->schema([
                    Select::make('status')->options(ApplicationStatus::options())->required()->native(false),
                    TextEntry::make('edition_year')->label('Edition'),
                    TextEntry::make('roles')->label('Roles requested')->badge()->formatStateUsing(fn (string $state): string => VolunteerRoles::options()[$state] ?? $state)->placeholder('None'),
                    Select::make('assigned_roles')->label('Assigned roles')->options(VolunteerRoles::options())->multiple()
                        ->helperText('May differ from the roles requested.')->columnSpan(2),
                    TextEntry::make('maker_applications')->label('Also applied as Maker')
                        ->state(fn (?Application $record): ?string => $record?->makerApplicationsWithSameEmail()
                            ->map(fn (Application $maker): string => $maker->edition_year.' · '.(ApplicationStatus::options()[$maker->status] ?? $maker->status))->implode(', ') ?: null)
                        ->url(fn (?Application $record): ?string => ($maker = $record?->makerApplicationsWithSameEmail()->first()) ? MakerResource::getUrl('edit', ['record' => $maker]) : null)
                        ->color('warning')->icon('heroicon-o-paint-brush')
                        ->visible(fn (?Application $record): bool => (bool) $record?->makerApplicationsWithSameEmail()->isNotEmpty()),
                    Textarea::make('internal_notes')->label('Selection notes')->rows(4)->maxLength(20000)->columnSpanFull(),
                ])->columnSpanFull(),
            Section::make('Submitted application')
                ->description('Original answers are preserved. Operational edits above do not change this submission.')
                ->schema(fn (?Application $record): array => static::answerEntries($record))
                ->columns(2)->collapsible()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        $roleLabel = fn (string $state): string => VolunteerRoles::options()[$state] ?? $state;

        return $table
            ->columns([
                TextColumn::make('display_name')->label('Volunteer')->weight('medium')
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where(fn (Builder $query): Builder => $query
                        ->where('contact_name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%'))),
                TextColumn::make('roles')->label('Requested')->badge()->formatStateUsing($roleLabel),
                TextColumn::make('assigned_roles')->label('Assigned')->badge()->color('success')->formatStateUsing($roleLabel)->placeholder('Not assigned'),
                TextColumn::make('day_parts')->label('Can be there')
                    ->state(fn (Application $record): string => collect($record->answers['day_parts'] ?? [])->map(fn ($part) => ucfirst((string) $part))->implode(', ')),
                IconColumn::make('applied_as_maker')->label('Also Maker')->boolean()->trueIcon('heroicon-o-paint-brush')->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')->falseColor('gray')->tooltip(fn (Application $record): ?string => $record->applied_as_maker ? 'Also applied as Maker' : null),
                TextColumn::make('status')->badge()->formatStateUsing(fn (string $state): string => ApplicationStatus::options()[$state] ?? $state)
                    ->color(fn (string $state): string => ApplicationStatus::color($state))->sortable(),
                TextColumn::make('edition_year')->label('Edition')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Applied')->date('j M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('edition_year')->label('Edition')->options(fn (): array => Application::query()->where('audience', static::$audience)->distinct()->orderByDesc('edition_year')->pluck('edition_year', 'edition_year')->all()),
                SelectFilter::make('status')->options(ApplicationStatus::options()),
                SelectFilter::make('role')->label('Role requested')->options(VolunteerRoles::options())->query(fn (Builder $query, array $data): Builder => $query->when($data['value'] ?? null,
                    fn (Builder $query, string $role): Builder => $query->whereJsonContains('roles', $role))),
                TernaryFilter::make('assigned')->label('Role assignment')->placeholder('All applications')->trueLabel('Assigned a role')->falseLabel('Not assigned')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNotNull('assigned_roles')->where('assigned_roles', '!=', '[]'),
                        false: fn (Builder $query): Builder => $query->where(fn (Builder $query): Builder => $query->whereNull('assigned_roles')->orWhere('assigned_roles', '[]')),
                    ),
            ])
            ->recordActions([EditAction::make()->label('Open')])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No volunteer applications yet')
            ->emptyStateDescription('Applications submitted through the website will appear here.')
            ->recordUrl(fn (Application $record): string => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListVolunteers::route('/'), 'edit' => Pages\EditVolunteer::route('/{record}/edit')];
    }
}
