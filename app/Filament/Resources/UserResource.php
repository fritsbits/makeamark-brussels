<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'users';

    protected static ?string $navigationLabel = 'Admin accounts';

    protected static ?string $modelLabel = 'admin account';

    protected static ?string $pluralModelLabel = 'Admin accounts';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->label('Email address')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
            TextInput::make('password')
                ->password()
                ->revealable()
                ->autocomplete('new-password')
                ->required(fn (string $operation): bool => $operation === 'create')
                ->rules([Password::min(12)->mixedCase()->numbers()->symbols()])
                ->afterStateHydrated(fn (TextInput $component) => $component->state(null))
                ->dehydrated(fn (?string $state): bool => filled($state))
                ->helperText('Use a unique password with at least 12 characters, mixed case, a number and a symbol. Leave blank when editing to keep the current password.'),
            Toggle::make('is_active')->label('Active')->default(true)
                ->helperText('Deactivate an account to remove access. Your own account must remain active.'),
            Toggle::make('is_super_admin')->label('Super admin')->default(false)
                ->helperText('Super admins can manage admin accounts. You cannot remove your own super admin access.'),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->label('Email address')->searchable()->sortable(),
                IconColumn::make('is_active')->label('Active')->boolean(),
                IconColumn::make('is_super_admin')->label('Super admin')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('is_super_admin')->label('Super admin'),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('name');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(fn (Builder $query) => $query->where('is_admin', true)->orWhere('is_super_admin', true));
    }

    private static function isActiveSuperAdmin(): bool
    {
        $user = Filament::auth()->user();

        return $user instanceof User && $user->is_active && $user->is_super_admin;
    }

    public static function canViewAny(): bool
    {
        return static::isActiveSuperAdmin() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return static::isActiveSuperAdmin() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return static::isActiveSuperAdmin() && parent::canEdit($record);
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
