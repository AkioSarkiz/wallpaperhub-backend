<?php

declare(strict_types=1);

namespace App\MediaLibrary\Support;

use App\MediaLibrary\Support\UrlGeneratorFactory\DefaultUrlGeneratorFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    public function getUrl(string $conversionName = ''): string
    {
        $urlGenerator = DefaultUrlGeneratorFactory::createForMedia($this, $conversionName);

        return $urlGenerator->getUrl();
    }
}
