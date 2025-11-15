<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\WallpaperTag;

class WallpaperTagService
{
    public function create(array $data): WallpaperTag
    {
        return WallpaperTag::create($data);
    }

    public function findByTitle(string $title): ?WallpaperTag
    {
        return WallpaperTag::where('title', $title)->first();
    }
}
