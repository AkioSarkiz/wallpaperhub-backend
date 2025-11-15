<?php

declare(strict_types=1);

namespace Database\Seeders;

use accudio\PHPPlaiceholder\PHPPlaiceholder;
use App\Models\User;
use App\Models\Wallpaper;
use App\Services\UsersService;
use App\Services\WallpaperService;
use App\Services\WallpaperTagService;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\LocalFilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WallpaperSeeder extends Seeder
{
    public function __construct(
        private readonly WallpaperService $wallpaperService,
        private readonly WallpaperTagService $wallpaperTagService,
        private readonly UsersService $usersService,
    ) {
        //
    }

    private function getStorageName(): string
    {
        return 'seed';
    }

    private function getStorage(): Filesystem|LocalFilesystemAdapter
    {
        return Storage::disk($this->getStorageName());
    }

    private function warnLog(string $message): void
    {
        Log::warning($message);
        echo $message;
    }

    private function getUser(): User
    {
        $seedUserName = env('SEED_USER_NAME', 'Dmytro');
        $seedUserEmail = env('SEED_USER_EMAIL', 'akiosarkiz@gmail.com');
        $user = $this->usersService->findByEmail($seedUserEmail);

        if ($user) {
            return $user;
        }

        return User::factory()->create([
            'name' => $seedUserName,
            'email' => $seedUserEmail,
        ]);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $directory = '/wallpapers/';
        $files = $this->getStorage()->files($directory);

        foreach ($files as $filepath) {
            $filename = basename($filepath);

            if (str_ends_with($filename, '.jpg')) {
                $jsonFilename = str_replace('.jpg', '', $filename) . '.json';
                $jsonFilepath = $directory . $jsonFilename;

                if (!$this->getStorage()->exists($jsonFilepath)) {
                    $this->warnLog("Not found json file $jsonFilename");

                    continue;
                }

                $jsonData = json_decode($this->getStorage()->get($jsonFilepath), true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->warnLog("Could not parse json file $jsonFilename");

                    continue;
                }

                if (!$this->validateJsonData($jsonData)) {
                    $this->warnLog("Invalid json file $jsonFilename");

                    continue;
                }

                $wallpaper = $this->wallpaperService->create(
                    array_merge(
                        Arr::only($jsonData, [
                            'title',
                            'description',
                        ]),
                        [
                            'user_id' => $this->getUser()->id,
                        ]
                    )
                );

                $this->attachTags($wallpaper, $jsonData['tags']);

                $placeholder = new PHPPlaiceholder($this->getStorage()->path($filepath));
                [$width, $height] = getimagesize($this->getStorage()->path($filepath));

                $wallpaper
                    ->addMediaFromDisk($filepath, $this->getStorageName())
                    ->withCustomProperties([
                        'placeholder_blurhash' => $placeholder->get_blurhash(),
                        'width' => $width,
                        'height' => $height,
                    ])
                    ->withProperties([
                        'uuid' => Str::uuid(),
                    ])
                    ->toMediaCollection('wallpaper');
            }
        }
    }

    private function validateJsonData(array $jsonData): bool
    {
        $result = Validator::make($jsonData, [
            'filename' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'tags' => 'required|array',
            'tags.*' => 'string',
        ]);

        return $result->passes();
    }

    private function attachTags(Wallpaper $wallpaper, array $wallpaperTagNames): void
    {
        foreach ($wallpaperTagNames as $wallpaperTagName) {
            $wallpaperTag = null;

            try {
                $wallpaperTag = $this->wallpaperTagService->create(['title' => $wallpaperTagName]);
            } catch (\Exception $e) {
                if ($e->getCode() == 23000) {
                    $wallpaperTag = $this->wallpaperTagService->findByTitle($wallpaperTagName);

                    if (!$wallpaperTag) {
                        $this->warnLog("Could not process tag $wallpaperTagName");

                        continue;
                    }
                }
            }

            $wallpaper->tags()->attach($wallpaperTag);
        }
    }
}
