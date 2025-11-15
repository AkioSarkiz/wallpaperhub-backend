<?php

declare(strict_types=1);

namespace App\Filament\Resources\Wallpapers\Pages;

use App\Filament\Resources\Wallpapers\WallpaperResource;
use App\Services\WallpaperService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditWallpaper extends EditRecord
{
    protected static string $resource = WallpaperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $service = app(WallpaperService::class);
        $service->update($record, $data);

        return $record;
    }
}
