<?php

declare(strict_types=1);

namespace App\Filament\Resources\Wallpapers\Pages;

use App\Filament\Resources\Wallpapers\WallpaperResource;
use App\Services\WallpaperService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWallpaper extends CreateRecord
{
    protected static string $resource = WallpaperResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $service = app(WallpaperService::class);

        return $service->create($data);
    }
}
