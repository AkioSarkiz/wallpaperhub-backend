<?php

declare(strict_types=1);

namespace App\Listeners;

use App\ClassObjects\TrackEventContext;
use App\Events\WallpaperViewedEvent;
use App\Services\TrackService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WallpaperViewedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly TrackService $trackService,
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WallpaperViewedEvent $event): void
    {
        $trackEventContext = new TrackEventContext(
            ip: $event->getUserContext()->getIp(),
            userAgent: $event->getUserContext()->getUserAgent(),
            tableName: 'wallpapers',
            tableId: $event->getWallpaper()->getKey(),
            eventName: 'view'
        );

        $this->trackService->trackEventWithContext($trackEventContext);
    }
}
