<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallpaper_wallpaper_tag', function (Blueprint $table) {
            $table->foreignId('wallpaper_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('wallpaper_tag_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallpaper_wallpaper_tag');
    }
};
