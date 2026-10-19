<?php

namespace App\Filament\Support;

use App\Models\Application;
use App\Support\ApplicationStatus;
use App\Support\Skills;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string $audience;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('audience', static::$audience)
            ->with(['reviews.user', 'teams', 'organisationTeam'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getRecordTitle(?Model $record): string
    {
        return $record?->display_name ?? static::getModelLabel();
    }

    public static function form(Schema $schema): Schema
    {
        $organisation = static::$audience === 'organisations';

        return $schema->components([
            Section::make('Selection')
                ->description('Status changes save internally. They do not send emails.')
                ->columns(3)
                ->schema([
                    Select::make('status')->options(ApplicationStatus::options())->required()->native(false),
                    TextEntry::make('edition_year')->label('Edition'),
                    TextEntry::make($organisation ? 'organisationTeam.name' : 'teams.name')->label('Team')->placeholder('Not assigned'),
                ])->columnSpanFull(),
            Section::make($organisation ? 'Refined briefing' : 'Skills and selection notes')
                ->description($organisation
                    ? 'Refine the brief after your meeting. Gwen uses this and the skills needed for matchmaking.'
                    : 'Use the shared skill labels to find suitable teams. The original application stays below.')
                ->schema($organisation ? [
                    Textarea::make('refined_brief')->label('Briefing for makers')->rows(7)->maxLength(20000),
                    Select::make('skills_needed')->label('Skills needed')->options(Skills::options())->multiple()->searchable(),
                    Textarea::make('internal_notes')->label('Contact and internal notes')->helperText('Organisation contact stays in your own mailbox.')->rows(4)->maxLength(20000),
                ] : [
                    Select::make('main_skills')->options(Skills::options())->multiple()->searchable(),
                    Select::make('backup_skills')->options(Skills::options())->multiple()->searchable(),
                    Textarea::make('internal_notes')->label('Selection notes')->rows(4)->maxLength(20000),
                ])->columnSpanFull(),
            Section::make('Organisation reviews')->visible($organisation)
                ->description('Separate assessments support consensus. Use “My review” to add or change your own.')
                ->schema([
                    TextEntry::make('reviews_avg_rating')->label('Average rating')->numeric(decimalPlaces: 1)->placeholder('No ratings yet'),
                    RepeatableEntry::make('reviews')->label('Assessments')->schema([
                        TextEntry::make('user.name')->label('Reviewer'),
                        TextEntry::make('rating')->suffix(' / 5'),
                        TextEntry::make('comment')->placeholder('No comment')->columnSpanFull(),
                    ])->columns(2)->placeholder('No reviews yet'),
                ])->columnSpanFull(),
            Section::make('Submitted application')
                ->description('Original answers are preserved. Operational edits above do not change this submission.')
                ->schema(fn (?Application $record): array => static::answerEntries($record))
                ->columns(2)->collapsible()->columnSpanFull(),
        ]);
    }

    private static function answerEntries(?Application $record): array
    {
        if (! $record) {
            return [];
        }

        $checks = ['brussels_eligibility', 'youth_brussels_eligibility', 'assets_ready', 'planning_meeting', 'feedback_session', 'evening_presentation', 'date_confirmed', 'full_commitment'];
        $entries = [
            TextEntry::make('contact_name')->label('Contact name'),
            TextEntry::make('email')->label('Email')->copyable(),
            TextEntry::make('created_at')->label('Applied')->dateTime('j M Y, H:i'),
        ];

        foreach ($record->answers ?? [] as $key => $value) {
            if (in_array($key, ['name', 'email', 'contact_name'], true)) {
                continue;
            }

            $value = is_array($value) ? implode(', ', array_filter($value, 'is_scalar')) : (is_scalar($value) ? (string) $value : '');
            if (in_array($key, $checks, true)) {
                $value = in_array($value, ['1', 'true', 'yes'], true) ? 'Yes' : 'No';
            }

            $label = match ($key) {
                'brussels_eligibility' => 'Supports people in Brussels (original confirmation)',
                'youth_brussels_eligibility' => 'Works with young people in Brussels',
                default => Str::headline($key),
            };
            $entry = TextEntry::make('submitted_'.$key)->label($label)->state($value)->placeholder('Not supplied');
            if (in_array($key, ['portfolio', 'website'], true) && filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true)) {
                $entry->url($value)->openUrlInNewTab();
            }
            if (in_array($key, ['mission', 'challenge', 'motivation', 'other_skills', 'team_preferences'], true)) {
                $entry->columnSpanFull();
            }
            $entries[] = $entry;
        }

        return $entries;
    }

    public static function table(Table $table): Table
    {
        $organisation = static::$audience === 'organisations';

        return $table
            ->columns([
                TextColumn::make('display_name')->label($organisation ? 'Organisation' : 'Maker')->weight('medium')
                    ->searchable(query: fn (Builder $query, string $search): Builder => $query->where(fn (Builder $query): Builder => $query
                        ->where('contact_name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('answers->organisation_name', 'like', '%'.$search.'%'))),
                TextColumn::make('edition_year')->label('Edition')->sortable(),
                TextColumn::make('status')->badge()->formatStateUsing(fn (string $state): string => ApplicationStatus::options()[$state] ?? $state)
                    ->color(fn (string $state): string => ApplicationStatus::color($state))->sortable(),
                TextColumn::make('reviews_avg_rating')->label('Average')->numeric(decimalPlaces: 1)->placeholder('—')->sortable()->visible($organisation),
                TextColumn::make('reviews_count')->label('Reviews')->visible($organisation),
                TextColumn::make('my_rating')->label('My rating')->state(fn (Application $record) => $record->reviews->firstWhere('user_id', auth()->id())?->rating)->placeholder('Not reviewed')->visible($organisation),
                TextColumn::make('skills_needed')->label('Skills needed')->badge()->formatStateUsing(fn (string $state): string => Skills::options()[$state] ?? $state)->visible($organisation)->limitList(3)->expandableLimitedList(),
                TextColumn::make('main_skills')->badge()->formatStateUsing(fn (string $state): string => Skills::options()[$state] ?? $state)->visible(! $organisation)->limitList(3)->expandableLimitedList(),
                TextColumn::make($organisation ? 'organisationTeam.name' : 'teams.name')->label('Team')->placeholder('Not assigned'),
                TextColumn::make('created_at')->label('Applied')->date('j M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('edition_year')->label('Edition')->options(fn (): array => Application::query()->where('audience', static::$audience)->distinct()->orderByDesc('edition_year')->pluck('edition_year', 'edition_year')->all()),
                SelectFilter::make('status')->options(ApplicationStatus::options()),
                SelectFilter::make('reviewed')->label('My review')->options(['yes' => 'Reviewed by me', 'no' => 'Not reviewed by me'])->visible($organisation)
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(($data['value'] ?? null) === 'yes', fn (Builder $query): Builder => $query->whereHas('reviews', fn (Builder $query): Builder => $query->where('user_id', auth()->id())))
                        ->when(($data['value'] ?? null) === 'no', fn (Builder $query): Builder => $query->whereDoesntHave('reviews', fn (Builder $query): Builder => $query->where('user_id', auth()->id())))),
                SelectFilter::make('skill')->options(Skills::options())->label('Skill')->query(fn (Builder $query, array $data): Builder => $query->when($data['value'] ?? null,
                    fn (Builder $query, string $skill): Builder => $query->where(fn (Builder $query): Builder => $organisation
                        ? $query->whereJsonContains('skills_needed', $skill)
                        : $query->whereJsonContains('main_skills', $skill)->orWhereJsonContains('backup_skills', $skill)))),
                TernaryFilter::make('assigned')->label('Team assignment')->placeholder('All applications')->trueLabel('Assigned to a team')->falseLabel('Not assigned')
                    ->queries(true: fn (Builder $query): Builder => $query->has($organisation ? 'organisationTeam' : 'teams'), false: fn (Builder $query): Builder => $query->doesntHave($organisation ? 'organisationTeam' : 'teams')),
            ])
            ->recordActions([EditAction::make()->label($organisation ? 'Review' : 'Open')])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading($organisation ? 'No organisation applications yet' : 'No maker applications yet')
            ->emptyStateDescription('Applications submitted through the website will appear here.')
            ->recordUrl(fn (Application $record): string => static::getUrl('edit', ['record' => $record]));
    }
}
