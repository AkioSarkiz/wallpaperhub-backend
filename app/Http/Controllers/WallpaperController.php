<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\WallpaperResource;
use App\Models\Wallpaper;
use App\Services\WallpaperService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class WallpaperController extends Controller
{
    public function __construct(
        private readonly WallpaperService $wallpaperService,
    ) {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Wallpaper::class);

        $wallpapers = $this->wallpaperService->getPagination();

        return WallpaperResource::collection($wallpapers);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $wallpaperId): WallpaperResource
    {
        $wallpaper = $this->wallpaperService->findOrFail($wallpaperId);

        Gate::authorize('view', $wallpaper);

        return new WallpaperResource($wallpaper, true);
    }

    public function search()
    {
        Gate::authorize('viewAny', Wallpaper::class);

        $wallpapers = $this->wallpaperService->getSearchPagination();

        return WallpaperResource::collection($wallpapers);
    }
}
