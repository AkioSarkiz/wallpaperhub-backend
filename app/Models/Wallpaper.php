<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\Filterable;
use Arr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $total_downloads
 * @property int $total_views
 * @property int $total_likes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WallpaperTag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\WallpaperFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper filter(\App\Contracts\FilterInterface $filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereTotalDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereTotalLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereTotalViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wallpaper whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Wallpaper extends Model implements HasMedia
{
    use Filterable, InteractsWithMedia, Searchable;

    /** @use HasFactory<\Database\Factories\WallpaperFactory> */
    use HasFactory;

    public const SUPPORTED_MIME_TYPES = ['image/png', 'image/jpeg'];

    public const SUPPORTED_MEDIA_CONVERSION_FORMATS = [
        'jpg',
        'webp',
        'avif',
    ];

    public const MEDIA_CONVERSION_QUALITY = 90;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',

        'total_downloads',
        'total_views',
        'total_likes',

        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(WallpaperTag::class);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('wallpaper')
            ->useDisk('s3')
            ->singleFile()
            ->acceptsFile(fn(File $file) => in_array($file->mimeType, static::SUPPORTED_MIME_TYPES))
            ->registerMediaConversions(function (Media $media) {
                $tempPath = tempnam(sys_get_temp_dir(), 'media_');
                $stream = Storage::disk($media->disk)->readStream($media->getPath());

                file_put_contents(
                    $tempPath,
                    $stream
                );

                [$imageSizeWidth, $imageSizeHeight] = @getimagesize($tempPath);

                unlink($tempPath);

                $this
                    ->addMediaConversion('thumbnail')
                    ->fit(Fit::Fill)
                    ->width(400)
                    ->height(200)
                    ->quality(static::MEDIA_CONVERSION_QUALITY);

                for ($i = 0; $i < 3; $i++) {
                    foreach (static::SUPPORTED_MEDIA_CONVERSION_FORMATS as $conversionFormat) {
                        $width = 1200 * ($i + 1);
                        $height = 600 * ($i + 1);

                        if ($width <= $imageSizeWidth || $height <= $imageSizeHeight) {
                            $this
                                ->addMediaConversion(sprintf('preview@%d--%s--', $i + 1, $conversionFormat))
                                ->fit(Fit::Fill)
                                ->width($width)
                                ->height($height)
                                ->format($conversionFormat)
                                ->quality(static::MEDIA_CONVERSION_QUALITY)
                                ->optimize()
                                ->shouldBeQueued();
                        }

                        $width = 400 * ($i + 1);
                        $height = 200 * ($i + 1);

                        if ($width <= $imageSizeWidth || $height <= $imageSizeHeight) {
                            $this
                                ->addMediaConversion(sprintf('thumbnail@%d--%s--', $i + 1, $conversionFormat))
                                ->fit(Fit::Fill)
                                ->width($width)
                                ->height($height)
                                ->format($conversionFormat)
                                ->quality(static::MEDIA_CONVERSION_QUALITY)
                                ->optimize()
                                ->shouldBeQueued();
                        }
                    }
                }
            });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $searchableData = Arr::only($this->toArray(), ['created_at', 'title', 'description']);
        $searchableData['tags'] = $this->tags->pluck('title')->toArray();

        return $searchableData;
    }
}
