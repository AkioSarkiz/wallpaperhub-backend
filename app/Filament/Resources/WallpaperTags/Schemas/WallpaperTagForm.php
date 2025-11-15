<?php

declare(strict_types=1);

namespace App\Filament\Resources\WallpaperTags\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WallpaperTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->columnSpanFull()
                    ->required(),

                Checkbox::make('is_featured')
                    ->default(false),
            ]);
    }
}
