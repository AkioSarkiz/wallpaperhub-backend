<?php

namespace App\Filament\Resources\WallpaperTags\Pages;

use App\Filament\Resources\WallpaperTags\WallpaperTagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWallpaperTag extends EditRecord
{
    protected static string $resource = WallpaperTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
