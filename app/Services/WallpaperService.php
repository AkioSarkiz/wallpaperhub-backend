<?php

declare(strict_types=1);

namespace App\Services;

use App\ClassObjects\UserContext;
use App\Events\WallpaperViewedEvent;
use App\Models\Filters\WallpaperFilter;
use App\Models\Wallpaper;
use Illuminate\Pagination\Paginator;

class WallpaperService
{
    public function create(array $data): Wallpaper
    {
        return Wallpaper::create($data);
    }

    public function update(Wallpaper $wallpaper, array $data): Wallpaper
    {
        $wallpaper->update();

        return $wallpaper;
    }

    public function getPagination(): Paginator
    {
        $wallpapers = Wallpaper::with('media')
            ->select(['id'])
            ->limit(10)
            ->filter(app(WallpaperFilter::class))
            ->simplePaginate();

        return $wallpapers;
    }

    public function getSearchPagination(): Paginator
    {
        return Wallpaper::search(request()->query("q"))
            ->take(10)
            ->query(function ($query) {
                return $query->with("media")->select(["id"]);
            })
            ->simplePaginate();
    }

    public function increaseViewCount(int $wallpaperId): void
    {
        Wallpaper::where('id', $wallpaperId)->increment('total_views');
    }

    public function findOrFail(int $id): Wallpaper
    {
        $wallpaper = Wallpaper::with('user:id,name', 'tags:id,title', 'media')
            ->select(['id', 'user_id', 'title', 'description', 'total_likes', 'total_views', 'total_downloads'])
            ->findOrFail($id);

        WallpaperViewedEvent::dispatch(
            $wallpaper->withoutRelations(),
            new UserContext(request()->ip(), request()->userAgent())
        );

        return $wallpaper;
    }
}
