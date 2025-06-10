<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SetResource\Pages;
use App\Models\Set;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class SetResource extends Resource
{
    protected static ?string $model = Set::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    public static function getNavigationLabel(): string
    {
        return __('resources.resources.sets.label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.resources.sets.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.resources.sets.plural');
    }

    public static function form(Form $form): Form
    {
        $schema = [
            // Ortak alanlar
            TextInput::make('name')
                ->label(__('resources.fields.name'))
                ->required()
                ->maxLength(255)
                ->dehydrateStateUsing(fn($state) => is_array($state) ? json_encode($state) : $state)
                ->reactive(),
            TextInput::make('description')
                ->label(__('resources.fields.description'))
                ->nullable()
                ->maxLength(500)
                ->dehydrateStateUsing(fn($state) => is_array($state) ? json_encode($state) : $state)
                ->reactive(),
            FileUpload::make('cover_image')
                ->image()
                ->label('Kapak Görseli'),
            FileUpload::make('audio_file')
                ->label('Set Dosyası')
                ->directory('sets')
                ->acceptedFileTypes(['audio/mpeg', 'audio/wav'])
                ->maxSize(10240),
            Toggle::make('is_premium')
                ->label('Premium Erişim'),
            TextInput::make('iap_product_id')
                ->label('IAP Ürün Kodu')
                ->nullable(),
        ];

        // Kullanıcı rolüne göre user_id alanı
        if (auth()->user()?->hasRole('admin')) {
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
                ->default(auth()->id());
        }

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
                TextColumn::make('name')
                    ->label(__('resources.fields.name'))
                    ->formatStateUsing(function ($state, $record) {
                        $locale = app()->getLocale();
                        return is_array($record->name)
                            ? ($record->name[$locale] ?? array_values($record->name)[0] ?? '-')
                            : $record->name;
                    }),
                TextColumn::make('user.name')
                    ->label('DJ')
                    ->visible(auth()->user()?->hasRole('admin')), // Sadece admin görsün
                ToggleColumn::make('is_premium')
                    ->label('Premium'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => auth()->user()->hasRole('admin') || $record->user_id === auth()->id()),
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('dj') || auth()->user()?->hasRole('admin');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->hasRole('dj')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSets::route('/'),
            'create' => Pages\CreateSet::route('/create'),
            'edit' => Pages\EditSet::route('/{record}/edit'),
        ];
    }
}
