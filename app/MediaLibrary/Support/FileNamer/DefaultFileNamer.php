<?php

declare(strict_types=1);

namespace App\MediaLibrary\Support\FileNamer;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Support\FileNamer\FileNamer;

class DefaultFileNamer extends FileNamer
{
    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $conversionName = Str::replace(["--jpg--", "--jpeg--", "--webp--", "--avif--", "--png--"], "", $conversion->getName());

        return "{$strippedFileName}-{$conversionName}";
    }

    public function responsiveFileName(string $fileName): string
    {
        return pathinfo($fileName, PATHINFO_FILENAME);
    }
}
