<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wallpaper> $wallpapers
 * @property-read int|null $wallpapers_count
 *
 * @method static \Database\Factories\WallpaperTagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WallpaperTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WallpaperTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WallpaperTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WallpaperTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WallpaperTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WallpaperTag whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WallpaperTag whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class WallpaperTag extends Model
{
    /** @use HasFactory<\Database\Factories\WallpaperTagFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'is_featured',
    ];

    public function wallpapers(): BelongsToMany
    {
        return $this->belongsToMany(Wallpaper::class);
    }
}
