<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder; // ✅ doğru!
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    public static function getNavigationLabel(): string
    {
        return __('resources.resources.users.label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.resources.users.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.resources.users.plural');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->role('dj');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('resources.fields.name'))
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => is_array($state) ? json_encode($state) : $state)
                    ->reactive(),
                TextInput::make('bio')
                    ->label(__('resources.fields.bio'))
                    ->nullable()
                    ->maxLength(500)
                    ->dehydrateStateUsing(fn($state) => is_array($state) ? json_encode($state) : $state)
                    ->reactive(),
                FileUpload::make('profile_photo')->label('Profil Fotoğrafı')->image()->directory('users'),
                FileUpload::make('cover_image')->label('Kapak Görseli')->image()->directory('users/covers'),

                TextInput::make('instagram')->nullable(),
                TextInput::make('twitter')->nullable(),
                TextInput::make('facebook')->nullable(),
                TextInput::make('tiktok')->nullable(),
                TextInput::make('soundcloud')->nullable(),
                TextInput::make('email')->email()->required(),
                TextInput::make('password')
                    ->password()
                    ->nullable()
                    ->minLength(4)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => !empty($state)) // Şifre boşsa güncellemeye dahil etme
                    ->label('Şifre'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                ImageColumn::make('profile_photo')->label('Foto'),
                TextColumn::make('name')
                    ->label(__('resources.fields.name'))
                    ->formatStateUsing(function ($state, $record) {
                        $locale = app()->getLocale();
                        return is_array($record->name)
                            ? ($record->name[$locale] ?? array_values($record->name)[0] ?? '-')
                            : $record->name;
                    }),
                TextColumn::make('email'),
            ])
            ->defaultSort('created_at', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }


    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('admin');
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
