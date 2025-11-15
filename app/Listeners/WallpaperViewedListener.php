<?php

declare(strict_types=1);

namespace App\Listeners;

use App\ClassObjects\UserContext;
use App\Events\WallpaperViewedEvent;
use App\Services\WallpaperService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class WallpaperViewedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly WallpaperService $wallpaperService
    ) {
        //
    }

    private function getCacheKey(UserContext $userContext): string
    {
        return sprintf('wallpaper_viewed_listener_%s_%s', $userContext->getIp(), $userContext->getUserAgent());
    }

    /**
     * Handle the event.
     */
    public function handle(WallpaperViewedEvent $event): void
    {
        $cacheKey = $this->getCacheKey($event->getUserContext());

        if (Cache::has($cacheKey)) {
            // Throttle user views.
            return;
        }

        Cache::put($cacheKey, true, now()->addMinutes(5));

        $this->wallpaperService->increaseViewCount($event->getWallpaper()->id);
    }
}
