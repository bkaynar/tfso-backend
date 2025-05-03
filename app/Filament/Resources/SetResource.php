<?php
namespace App\Filament\Resources;

use App\Filament\Resources\SetResource\Pages;
use App\Models\Set;
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
use Filament\Forms\Components\Repeater;
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
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(auth()->id()),

                // Çok dilli alan: name
                Grid::make(2)
                    ->schema([
                        TextInput::make('name.tr')
                            ->label(__('resources.fields.name').' (Türkçe)')
                            ->required(),

                        TextInput::make('name.en')
                            ->label(__('resources.fields.name').' (English)')
                            ->required(),

                        TextInput::make('name.ru')
                            ->label(__('resources.fields.name').' (Русский)')
                            ->required(),

                        TextInput::make('name.he')
                            ->label(__('resources.fields.name').' (עברית)')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Grid::make(2)
                    ->schema([
                        Textarea::make('description.tr')
                            ->label(__('resources.fields.description').' (Türkçe)')
                            ->required(),

                        Textarea::make('description.en')
                            ->label(__('resources.fields.description').' (English)')
                            ->required(),

                        Textarea::make('description.ru')
                            ->label(__('resources.fields.description').' (Русский)')
                            ->required(),

                        Textarea::make('description.he')
                            ->label(__('resources.fields.description').' (עברית)')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->image()
                    ->label('Kapak Görseli'),
                FileUpload::make('audio_file')
                    ->label('Set Dosyası')
                    ->directory('sets')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav'])
                    ->maxSize(10240),

                Toggle::make('is_premium')->label('Premium Erişim'),
                TextInput::make('iap_product_id')->label('IAP Ürün Kodu')->nullable(),
            ]);
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
                ToggleColumn::make('is_premium')->label('Premium'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
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
