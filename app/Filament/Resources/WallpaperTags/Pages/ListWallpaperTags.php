<?php

namespace App\Filament\Resources\WallpaperTags\Pages;

use App\Filament\Resources\WallpaperTags\WallpaperTagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWallpaperTags extends ListRecords
{
    protected static string $resource = WallpaperTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
