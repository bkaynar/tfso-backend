<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackResource\Pages;
use App\Models\Track;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Auth;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    public static function getNavigationLabel(): string
    {
        return __('resources.resources.tracks.label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.resources.tracks.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.resources.tracks.plural');
    }

    public static function form(Form $form): Form
    {
        $schema = [
            // Ortak alanlar
          TextInput::make('name')
                ->label(__('resources.fields.name'))
                ->required(),
            TextInput::make('description')
                ->label(__('resources.fields.description'))
                ->required()
                ->maxLength(500),
            FileUpload::make('audio_file')
                ->label('Parça Dosyası')
                ->disk('public')
                ->directory('tracks')
                ->preserveFilenames()
                ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/x-m4a']),
            Toggle::make('is_premium')
                ->label('Premium'),
     
        ];

        // Kullanıcı rolüne göre user_id alanı
        if (Auth::user()?->hasRole('admin')) {
            // Admin için: DJ seçimi
            $schema[] = Select::make('user_id')
                ->label('DJ')
                ->options(
                    User::role('dj')->pluck('name', 'id')->toArray()
                )
                ->required()
                ->searchable()
                ->preload();
        } else {
            // DJ için: Kendi ID'sini gizli alan olarak ekle
            $schema[] = Hidden::make('user_id')
                ->default(Auth::id());
        }

        // Kategori seçimi
        $schema[] = Select::make('category_id')
            ->label('Kategori')
            ->relationship('category', 'name')
            ->required();

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name->' . app()->getLocale())
                    ->label('Ad'),
                TextColumn::make('user.name')
                    ->label('DJ')
                    ->visible(Auth::user()?->hasRole('admin')), // Sadece admin görsün
                ToggleColumn::make('is_premium')
                    ->label('Premium'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => Auth::user()?->hasRole('admin') || $record->user_id === Auth::id()),
            ]);
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole('dj') || Auth::user()?->hasRole('admin');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->hasRole('dj')) {
            // DJ ise sadece kendi verisini görecek
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTracks::route('/'),
            'create' => Pages\CreateTrack::route('/create'),
            'edit' => Pages\EditTrack::route('/{record}/edit'),
        ];
    }
}
