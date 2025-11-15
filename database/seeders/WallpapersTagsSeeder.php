<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WallpapersTagsSeeder extends Seeder
{
    use WithoutModelEvents;

    private readonly array $tags = [
        "landscape",
        "serene",
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
