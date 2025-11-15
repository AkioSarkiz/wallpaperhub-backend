<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WallpaperResource extends JsonResource
{
    public function __construct($resource, private bool $detailed = false)
    {
        parent::__construct($resource);
    }

    private function getResponsiveMediaArray(Media $media, string $conversionName): array
    {
        $responsiveMedia = [];

        for ($i = 0; $i < 3; $i++) {
            foreach (Wallpaper::SUPPORTED_MEDIA_CONVERSION_FORMATS as $conversionFormat) {
                $conversionFullName = sprintf('%s@%d--%s--', $conversionName, $i + 1, $conversionFormat);

                if (!$media->hasGeneratedConversion($conversionFullName)) {
                    continue;
                }

                $responsiveMedia[] = [
                    "url" => $media->getFullUrl($conversionFullName),
                    "format" => $conversionFormat,
                    "index" => $i,
                ];
            }
        }

        return $responsiveMedia;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $wallpaper = $this->getFirstMedia('wallpaper');

        return [
            'id' => $this->id,
            'title' => $this->when($this->title, $this->title),
            'description' => $this->when($this->description, $this->description),

            'total_downloads' => $this->when($this->total_downloads !== null, $this->total_downloads),
            'total_views' => $this->when($this->total_views !== null, $this->total_views),
            'total_likes' => $this->when($this->total_likes !== null, $this->total_likes),

            'wallpaper_previews' => $this->when(
                $this->detailed,
                $this->whenLoaded('media', fn() => $this->getResponsiveMediaArray($wallpaper, 'preview'))
            ),

            'wallpaper_thumbnails' => $this->when(
                !$this->detailed,
                $this->whenLoaded('media', fn() => $this->getResponsiveMediaArray($wallpaper, 'thumbnail'))
            ),

            'user' => UserResource::make($this->whenLoaded('user')),
            'tags' => WallpaperTagResource::collection($this->whenLoaded('tags')),

            'wallpaper' => $this->whenLoaded(
                'media',
                fn(): WallpaperMediaResource => WallpaperMediaResource::make($wallpaper)
            ),
        ];
    }
}
