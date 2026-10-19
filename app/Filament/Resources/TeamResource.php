<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Application;
use App\Models\Team;
use App\Support\Skills;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['organisation', 'makers'])->withCount('makers');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Team')->columns(2)->schema([
                TextInput::make('name')->required()->maxLength(255),
                Select::make('status')->options(['draft' => 'Draft', 'confirmed' => 'Confirmed'])->default('draft')->required()->native(false),
                TextInput::make('edition_year')->label('Edition')->numeric()->integer()->minValue(2000)->maxValue(2100)->default(2027)->required()->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set): void {
                        $set('organisation_application_id', null);
                        $set('maker_ids', []);
                    }),
                Select::make('organisation_application_id')->label('Organisation')->required()->searchable()->live()
                    ->options(fn (Get $get, ?Team $record): array => Application::query()->where('audience', 'organisations')
                        ->where('edition_year', $get('edition_year'))->whereIn('status', ['selected', 'confirmed'])
                        ->whereNotIn('id', Team::query()->when($record, fn (Builder $query): Builder => $query->whereKeyNot($record->id))->select('organisation_application_id'))
                        ->get()->mapWithKeys(fn (Application $application): array => [$application->id => $application->display_name])->all())
                    ->helperText('Selected or confirmed organisations in this edition that do not already have a team.'),
                Hidden::make('lock_version')->default(0),
            ])->columnSpanFull(),
            Section::make('Organisation briefing')->schema([
                TextEntry::make('briefing')->label('Briefing for makers')
                    ->state(fn (Get $get): ?string => static::selectedOrganisation($get)?->refined_brief)
                    ->placeholder('No refined briefing saved yet. Add it on the organisation application.'),
                TextEntry::make('needs')->label('Skills needed')->badge()
                    ->state(fn (Get $get): array => static::skillLabels(static::selectedOrganisation($get)?->skills_needed ?? [])),
                TextEntry::make('organisation_link')->label('Organisation application')->state('Open briefing and submitted application')
                    ->url(fn (Get $get): ?string => ($organisation = static::selectedOrganisation($get)) ? OrganisationResource::getUrl('edit', ['record' => $organisation]) : null)
                    ->visible(fn (Get $get): bool => static::selectedOrganisation($get) !== null),
            ])->columnSpanFull(),
            Section::make('Match makers')->description('Gwen leads matchmaking after the briefings are ready. All organisers can adjust teams.')
                ->columns(2)->schema([
                    Select::make('candidate_skill')->label('Filter candidates by skill')->options(Skills::options())->searchable()->live()->dehydrated(false),
                    Select::make('candidate_language')->label('Filter candidates by language')->options(['english' => 'English · level 3 or higher', 'dutch' => 'Dutch · level 3 or higher', 'french' => 'French · level 3 or higher'])->live()->dehydrated(false),
                    Select::make('maker_ids')->label('Makers')->multiple()->searchable()->live()->columnSpanFull()
                        ->options(fn (Get $get, ?Team $record): array => static::makerOptions($get, $record))
                        ->getOptionLabelsUsing(fn (array $values): array => Application::query()->where('audience', 'makers')->whereIn('id', $values)->get()
                            ->mapWithKeys(fn (Application $maker): array => [$maker->id => static::makerLabel($maker)])->all())
                        ->helperText('Only selected or confirmed makers in this edition are available. Makers assigned to another team must be removed there first.'),
                    TextEntry::make('main_coverage')->label('Main skills in this team')->badge()
                        ->state(fn (Get $get): array => static::skillLabels(static::selectedMakers($get)->flatMap(fn (Application $maker): array => $maker->main_skills ?? [])->unique()->all())),
                    TextEntry::make('backup_coverage')->label('Backup skills in this team')->badge()
                        ->state(fn (Get $get): array => static::skillLabels(static::selectedMakers($get)->flatMap(fn (Application $maker): array => $maker->backup_skills ?? [])->unique()->all())),
                    TextEntry::make('missing_skills')->label('Skills needed without coverage')->badge()->color('warning')
                        ->state(function (Get $get): array {
                            $covered = static::selectedMakers($get)->flatMap(fn (Application $maker): array => array_merge($maker->main_skills ?? [], $maker->backup_skills ?? []))->all();

                            return static::skillLabels(array_diff(static::selectedOrganisation($get)?->skills_needed ?? [], $covered));
                        })->placeholder('No uncovered skill needs'),
                    TextEntry::make('maker_count')->label('Makers in team')->state(fn (Get $get): int => count($get('maker_ids') ?? [])),
                    Textarea::make('notes')->label('Team notes')->rows(4)->maxLength(20000)->columnSpanFull(),
                ])->columnSpanFull(),
        ]);
    }

    private static function selectedOrganisation(Get $get): ?Application
    {
        return Application::query()->where('audience', 'organisations')->where('edition_year', $get('edition_year'))->find($get('organisation_application_id'));
    }

    private static function selectedMakers(Get $get): Collection
    {
        return Application::query()->where('audience', 'makers')->where('edition_year', $get('edition_year'))->whereIn('id', $get('maker_ids') ?? [])->get();
    }

    private static function makerOptions(Get $get, ?Team $record): array
    {
        return Application::query()->where('audience', 'makers')->where('edition_year', $get('edition_year'))
            ->whereIn('status', ['selected', 'confirmed'])
            ->whereDoesntHave('teams', fn (Builder $query): Builder => $query->when($record, fn (Builder $query): Builder => $query->whereKeyNot($record->id)))
            ->when($get('candidate_skill') || $get('candidate_language'), fn (Builder $query): Builder => $query->where(function (Builder $query) use ($get): void {
                $query->where(function (Builder $query) use ($get): void {
                    $query->when($get('candidate_skill'), fn (Builder $query, string $skill): Builder => $query->where(fn (Builder $query): Builder => $query->whereJsonContains('main_skills', $skill)->orWhereJsonContains('backup_skills', $skill)))
                        ->when($get('candidate_language'), function (Builder $query, string $language): Builder {
                            return in_array($language, ['english', 'dutch', 'french'], true) ? $query->where('answers->'.$language, '>=', '3') : $query->whereRaw('1 = 0');
                        });
                })->orWhereIn('id', $get('maker_ids') ?? []);
            }))
            ->orderBy('contact_name')->get()->mapWithKeys(fn (Application $maker): array => [$maker->id => static::makerLabel($maker)])->all();
    }

    private static function makerLabel(Application $maker): string
    {
        return $maker->display_name.' · '.implode(', ', static::skillLabels($maker->main_skills ?? []));
    }

    private static function skillLabels(array $skills): array
    {
        return array_values(array_map(fn (string $skill): string => Skills::options()[$skill] ?? $skill, $skills));
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable()->weight('medium'),
            TextColumn::make('edition_year')->label('Edition')->sortable(),
            TextColumn::make('organisation.display_name')->label('Organisation'),
            TextColumn::make('status')->badge()->color(fn (string $state): string => $state === 'confirmed' ? 'success' : 'warning'),
            TextColumn::make('makers_count')->label('Makers')->sortable(),
            TextColumn::make('makers.contact_name')->label('Team members')->listWithLineBreaks()->limitList(4)->expandableLimitedList(),
        ])->filters([
            SelectFilter::make('edition_year')->label('Edition')->options(fn (): array => Team::query()->distinct()->orderByDesc('edition_year')->pluck('edition_year', 'edition_year')->all()),
            SelectFilter::make('status')->options(['draft' => 'Draft', 'confirmed' => 'Confirmed']),
        ])->recordActions([EditAction::make()->label('Match team')])
            ->emptyStateHeading('No teams yet')->emptyStateDescription('Select an organisation and makers first, then create a team for their edition.')
            ->defaultSort('name')->recordUrl(fn (Team $record): string => static::getUrl('edit', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListTeams::route('/'), 'create' => Pages\CreateTeam::route('/create'), 'edit' => Pages\EditTeam::route('/{record}/edit')];
    }
}
