<?php

namespace App\Filament\Resources\WallpaperTags;

use App\Filament\Resources\WallpaperTags\Pages\CreateWallpaperTag;
use App\Filament\Resources\WallpaperTags\Pages\EditWallpaperTag;
use App\Filament\Resources\WallpaperTags\Pages\ListWallpaperTags;
use App\Filament\Resources\WallpaperTags\Schemas\WallpaperTagForm;
use App\Filament\Resources\WallpaperTags\Tables\WallpaperTagsTable;
use App\Models\WallpaperTag;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WallpaperTagResource extends Resource
{
    protected static ?string $model = WallpaperTag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return WallpaperTagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WallpaperTagsTable::configure($table);
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
            'index' => ListWallpaperTags::route('/'),
            'create' => CreateWallpaperTag::route('/create'),
            'edit' => EditWallpaperTag::route('/{record}/edit'),
        ];
    }
}
