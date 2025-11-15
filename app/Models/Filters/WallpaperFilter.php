<?php

declare(strict_types=1);

namespace App\Models\Filters;

use App\Http\Requests\Wallpapers\IndexWallpaperRequest;

class WallpaperFilter extends QueryFilter
{
    public function __construct(IndexWallpaperRequest $request)
    {
        parent::__construct($request);
    }

    public function limit(string $value): void
    {
        $this->builder->limit((int) $value);
    }

    public function offset(int $value): void
    {
        $this->builder->offset((int) $value);
    }
}
