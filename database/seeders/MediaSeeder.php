<?php

namespace Database\Seeders;

use App\Models\ContentSection;
use App\Models\PropertyType;
use App\Services\MediaUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class MediaSeeder extends Seeder
{
    private const ALLOWED = ['jpg', 'jpeg', 'png', 'webp', 'mp4', 'webm'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uploader = app(MediaUploader::class);

        $this->importFolder($uploader, 'property-types', PropertyType::class);
        $this->importFolder($uploader, 'content-sections', ContentSection::class);
    }

    /** @param class-string<Model> $modelClass */
    private function importFolder(MediaUploader $uploader, string $folder, string $modelClass): void
    {
        $base = __DIR__ . "/media/{$folder}";

        if (! File::isDirectory($base)) {
            $this->command->warn("No folder at {$base}, skipping.");

            return;
        }

        foreach (File::directories($base) as $directory) {
            $slug = basename($directory);
            $owner = $modelClass::where('slug', $slug)->first();

            if (! $owner) {
                $this->command->warn("[{$folder}] No record with slug \"{$slug}\", skipping that folder.");

                continue;
            }

            // Re-running the seeder must never duplicate files
            if ($owner->media()->exists()) {
                $this->command->line("[{$folder}] \"{$slug}\" already has files, skipping.");

                continue;
            }

            $files = collect(File::files($directory))
                ->filter(fn($file) => in_array(strtolower($file->getExtension()), self::ALLOWED, true))
                ->sortBy(fn($file) => $file->getFilename(), SORT_NATURAL | SORT_FLAG_CASE)
                ->values();

            // Single-file spots (a section's photo) take just the first file in the folder
            if ($owner->hasSingleMedia()) {
                $files = $files->take(1);
            }

            $count = 0;

            foreach ($files as $file) {
                // Wrap the file on disk as an "upload", so it goes through the exact same pipeline
                $upload = new UploadedFile(
                    $file->getRealPath(),
                    $file->getFilename(),
                    File::mimeType($file->getRealPath()),
                    null,
                    true, // test mode: accept a file that did not arrive over HTTP
                );

                try {
                    $media = $uploader->store($upload, $owner);
                    $media->update(['alt_text' => $this->altText($file->getFilename(), $owner)]);
                    $count++;
                } catch (Throwable $e) {
                    $this->command->error("[{$folder}] {$slug}/{$file->getFilename()}: {$e->getMessage()}");
                }
            }

            $this->command->info("[{$folder}] \"{$slug}\": {$count} of {$files->count()} files imported.");
        }
    }

    /** "02-rooftop-terrace.jpg" -> "Rooftop terrace" (falls back to the record's own name). */
    private function altText(string $filename, Model $owner): string
    {
        $text = Str::of(pathinfo($filename, PATHINFO_FILENAME))
            ->replaceMatches('/^\d+[\s._-]*/', '')
            ->replace(['-', '_'], ' ')
            ->squish()
            ->ucfirst()
            ->toString();

        return $text !== '' ? $text : (string) ($owner->getAttribute('name') ?? $owner->getAttribute('title'));
    }
}
