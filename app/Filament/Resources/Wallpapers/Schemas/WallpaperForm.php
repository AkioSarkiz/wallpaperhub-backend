<?php

declare(strict_types=1);

namespace App\Filament\Resources\Wallpapers\Schemas;

use accudio\PHPPlaiceholder\PHPPlaiceholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class WallpaperForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                Select::make('user_id')
                    ->label('User')
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tags')
                    ->label('Tags')
                    ->relationship(name: 'tags', titleAttribute: 'title')
                    ->multiple()
                    ->preload()
                    ->searchable(),

                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('wallpaper')
                    ->collection('wallpaper')
                    ->columnSpanFull()
                    ->customProperties(function (TemporaryUploadedFile $file): array {
                        $placeholder = new PHPPlaiceholder($file->getRealPath());
                        [$width, $height] = getimagesize($file->getRealPath());

                        return [
                            'placeholder_blurhash' => $placeholder->get_blurhash(),
                            'width' => $width,
                            'height' => $height,
                        ];
                    }),

                // hidden fields
                TextInput::make('total_downloads')
                    ->readOnly()
                    ->default(0)
                    ->numeric()
                    ->hidden(),
                TextInput::make('total_views')
                    ->readOnly()
                    ->default(0)
                    ->numeric()
                    ->hidden(),
                TextInput::make('total_likes')
                    ->readOnly()
                    ->default(0)
                    ->numeric()
                    ->hidden(),
            ]);
    }
}
