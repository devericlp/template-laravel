<?php

namespace App\Actions;

use Illuminate\Support\Facades\{File, Storage};
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class StoreImage
{
    private function persistImage(
        TemporaryUploadedFile $file,
        string $path,
        int $width = 500,
        int $height = 500,
        int $quality = 85,
        string $extension = 'jpg'
    ): string {
        $fullPath = '';

        if (!app()->environment('testing')) {
            $manager = new ImageManager(new Driver());

            $filename = uniqid() . '.' . $extension;
            $fullPath = rtrim($path, '/') . '/' . $filename;

            $image = $manager->read($file->getRealPath())
                ->scaleDown($width, $height)
                ->encodeByExtension($extension, $quality);

            Storage::disk('public')->put($fullPath, $image->toString());
        }

        return $fullPath;
    }

    private function checkPath($path)
    {
        $path = explode('/', $path);
        array_pop($path);
        $path = implode('/', $path);

        if (!File::exists($path)) {
            Storage::makeDirectory($path);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function handle($file, $path, $width = 500, $height = 500, $quality = 85, $extension = 'jpg')
    {
        return $this->persistImage($file, $path, $width, $height, $quality, $extension);
    }
}
