<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\NotImplementedException;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallpaper>
 */
class WallpaperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        throw new NotImplementedException;

        return [
            //
        ];
    }
}
