<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RadioResource\Pages;
use App\Models\Radio;
use Filament\Forms;
use Filament\Forms\Components\Grid;
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
use Filament\Tables\Columns\ToggleColumn;


class RadioResource extends Resource
{
    protected static ?string $model = Radio::class;

    protected static ?string $navigationIcon = 'heroicon-o-radio';

    public static function getNavigationLabel(): string
    {
        return __('resources.resources.radios.label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.resources.radios.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.resources.radios.plural');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                FileUpload::make('image')
                    ->label(__('resources.fields.image'))
                    ->disk('public')
                    ->directory('radios')
                    ->image(),

                TextInput::make('stream_url')
                    ->label(__('resources.fields.stream_url'))
                    ->required()
                    ->url(),

                Toggle::make('is_premium')->label(__('resources.fields.premium')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label(__('resources.fields.image'))->circular(),
                TextColumn::make('name')
                    ->label(__('resources.fields.name'))
                    ->formatStateUsing(function ($state, $record) {
                        $locale = app()->getLocale();
                        return is_array($record->name)
                            ? ($record->name[$locale] ?? array_values($record->name)[0] ?? '-')
                            : $record->name;
                    }),
                ToggleColumn::make('is_premium')->label(__('resources.fields.premium')),
                TextColumn::make('created_at')->label(__('resources.fields.created_at'))->dateTime(),
                TextColumn::make('updated_at')->label(__('resources.fields.updated_at'))->dateTime(),
                //Düzenleme ve Silme butonları
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }


    public static function canViewAny(): bool
    {
        return  auth()->user()?->hasRole('admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRadios::route('/'),
            'create' => Pages\CreateRadio::route('/create'),
            'edit' => Pages\EditRadio::route('/{record}/edit'),
        ];
    }
}

