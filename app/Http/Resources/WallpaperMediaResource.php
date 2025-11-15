<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WallpaperMediaResource extends JsonResource
{
    private function whenCustomProperty(string $propertyName)
    {
        return $this->when(
            $this->hasCustomProperty($propertyName),
            $this->getCustomProperty($propertyName)
        );
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->getFullUrl(),

            'metadata' => [
                'placeholder_blurhash' => $this->whenCustomProperty('placeholder_blurhash'),

                'width' => $this->whenCustomProperty('width'),
                'height' => $this->whenCustomProperty('height'),

                "size" => $this->size,
            ],
        ];
    }
}
