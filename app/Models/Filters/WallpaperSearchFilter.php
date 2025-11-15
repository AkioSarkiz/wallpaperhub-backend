<?php

declare(strict_types=1);

namespace App\Models\Filters;

use App\Contracts\SearchFilterInterface;
use App\Http\Requests\Wallpapers\IndexWallpaperRequest;
use App\Models\Wallpaper;

class WallpaperSearchFilter extends QueryFilter implements SearchFilterInterface
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

    public function search(string $value): void
    {
        dd($this->builder, Wallpaper::query());

        $this->builder->search($value);
    }
}
