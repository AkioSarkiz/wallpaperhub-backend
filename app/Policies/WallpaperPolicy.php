<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Wallpaper;

class WallpaperPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Wallpaper $wallpaper): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wallpaper $wallpaper): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wallpaper $wallpaper): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function like(?User $user): bool
    {
        return true;
    }
}
