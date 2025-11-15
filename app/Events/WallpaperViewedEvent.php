<?php

declare(strict_types=1);

namespace App\Events;

use App\ClassObjects\UserContext;
use App\Models\Wallpaper;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WallpaperViewedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private readonly Wallpaper $wallpaper,
        private readonly UserContext $userContext,
    ) {
        //
    }

    public function getWallpaper(): Wallpaper
    {
        return $this->wallpaper;
    }

    public function getUserContext(): UserContext
    {
        return $this->userContext;
    }
}
