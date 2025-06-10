<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    public static function getNavigationLabel(): string
    {
        return __('resources.resources.categories.label');
    }

    public static function getModelLabel(): string
    {
        return __('resources.resources.categories.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.resources.categories.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('resources.fields.name'))
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label(__('resources.fields.image'))
                    ->image()
                    ->disk('public')
                    ->directory('categories')
                    ->nullable()
                    ->maxSize(1024 * 5) // 5 MB
                    ->acceptedFileTypes(['image/*'])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('resources.fields.name')),
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('resources.fields.image')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user instanceof User && method_exists($user, 'hasRole') && $user->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user instanceof User && method_exists($user, 'hasRole') && $user->hasRole('admin');
    }

    public static function canEdit(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        $user = Auth::user();
        return $user instanceof User && method_exists($user, 'hasRole') && $user->hasRole('admin');
    }

    public static function canDelete(
        \Illuminate\Database\Eloquent\Model $record
    ): bool {
        $user = Auth::user();
        return $user instanceof User && method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}
