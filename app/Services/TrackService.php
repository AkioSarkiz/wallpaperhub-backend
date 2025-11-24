<?php

declare(strict_types=1);

namespace App\Services;

use App\ClassObjects\TrackEventContext;
use App\Http\Requests\Tracks\StoreTrackRequest;
use App\Models\Wallpaper;
use Illuminate\Support\Facades\Cache;

use function sprintf;

class TrackService
{
    public function __construct(
        private readonly WallpaperService $wallpaperService,
    ) {
        //
    }

    private function getTrackEventCacheKey(TrackEventContext $trackEventContext): string
    {
        return sprintf(
            'track_service_%s_%s_%s_%s_%s',
            $trackEventContext->getTableName(),
            $trackEventContext->getTableId(),
            $trackEventContext->getEventName(),
            $trackEventContext->getIp(),
            $trackEventContext->getUserAgent()
        );
    }

    public function trackEventWithContext(TrackEventContext $trackEventContext): void
    {
        $cacheKey = $this->getTrackEventCacheKey($trackEventContext);

        if (Cache::has($cacheKey)) {
            // Throttle user event.
            return;
        }

        Cache::put($cacheKey, true, now()->addMinutes(5));

        if ($trackEventContext->getTableName() === Wallpaper::make()->getTable()) {
            switch ($trackEventContext->getEventName()) {
                case 'download':
                    $this->wallpaperService->increaseDownloadsCount($trackEventContext->getTableId());

                    break;

                case 'view':
                    $this->wallpaperService->increaseViewCount($trackEventContext->getTableId());

                    break;
            }
        }
    }

    public function trackEventWithRequest(StoreTrackRequest $request): void
    {
        $trackEventContext = new TrackEventContext(
            ip: $request->ip(),
            userAgent: $request->userAgent(),
            tableName: $request->get('tableName'),
            tableId: (int) $request->get('tableId'),
            eventName: $request->get('eventName')
        );

        $this->trackEventWithContext($trackEventContext);
    }
}
