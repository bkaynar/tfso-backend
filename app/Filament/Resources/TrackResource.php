<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TrackResource\Pages;
use App\Models\Track;
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
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(auth()->id()),
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
                        TextInput::make('description.tr')
                            ->label(__('resources.fields.description').' (Türkçe)')
                            ->required(),

                        TextInput::make('description.en')
                            ->label(__('resources.fields.description').' (English)')
                            ->required(),

                        TextInput::make('description.ru')
                            ->label(__('resources.fields.description').' (Русский)')
                            ->required(),

                        TextInput::make('description.he')
                            ->label(__('resources.fields.description').' (עברית)')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                FileUpload::make('audio_file')
                    ->label('Parça Dosyası')
                    ->disk('public')
                    ->directory('tracks')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/png','audio/mpeg', 'audio/mp3', 'audio/x-m4a']),

                TextInput::make('duration')
                    ->numeric()
                    ->label('Süre (saniye)'),

                Toggle::make('is_premium')->label('Premium'),
                TextInput::make('iap_product_id')->label('IAP Ürün Kodu')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name->' . app()->getLocale())->label('Ad'),
                TextColumn::make('user.name->' . app()->getLocale())->label('DJ'),
                ToggleColumn::make('is_premium')->label('Premium'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->hasRole('dj')) {
            // DJ ise sadece kendi verisini görecek
            $query->where('user_id', auth()->id());
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
