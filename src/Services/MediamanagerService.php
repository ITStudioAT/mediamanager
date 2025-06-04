<?php

namespace Itstudioat\Mediamanager\src\Services;

use Carbon\Carbon;
use FilesystemIterator;
use getID3;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Spatie\Image\Enums\ImageDriver;

use Spatie\Image\Image;

/*
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Vips\Driver as VipsDriver;
*/

class MediaManagerService
{
    public function folderStructure($path = null)
    {

        $items = [
            'folders' => [],
            'files' => [],
        ];

        if (! File::exists($path)) {
            return $items;
        }

        $publicPath = rtrim(public_path(), '/\\');

        $normalizePath = function ($fullPath) use ($publicPath) {
            $relative = str_replace('\\', '/', str_replace($publicPath, '', $fullPath));

            return preg_replace('#/+#', '/', $relative); // remove double slashes
        };

        foreach (File::directories($path) as $dirPath) {

            // Skip "thumbs" only in the root media folder
            if (basename($dirPath) === '_thumbs') {
                continue;
            }

            $result = $this->countFilesAndFolders($dirPath);
            $items['folders'][] = [
                'type' => 'directory',
                'name' => basename($dirPath),
                'path' => $normalizePath($dirPath),
                'size' => null,
                'folders_count' => $result['folders'],
                'files_count' => $result['files'],
                'modified' => Carbon::createFromTimestamp(File::lastModified($dirPath))->toDateTimeString(),
            ];
        }

        foreach (File::files($path) as $file) {
            $fullPath = strtolower($file->getPathname());
            $mimeType = File::mimeType($fullPath);
            $info = [
                'type' => 'file',
                'name' => $file->getFilename(),
                'path' => $normalizePath($fullPath),
                'size' => $file->getSize(),
                'modified' => Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
                'extension' => strtolower($file->getExtension()),
                'mime_type' => $mimeType,
            ];

            // Image resolution
            if (str_starts_with($mimeType, 'image/')) {
                if ($dimensions = @getimagesize($fullPath)) {
                    $info['width'] = $dimensions[0];
                    $info['height'] = $dimensions[1];
                }
            }

            // Video resolution using getID3
            /**/
            if (str_starts_with($mimeType, 'video/')) {
                try {
                    $getID3 = new getID3();
                    $meta = $getID3->analyze($fullPath);
                    if (! empty($meta['video']['resolution_x']) && ! empty($meta['video']['resolution_y'])) {
                        $info['width'] = (int) $meta['video']['resolution_x'];
                        $info['height'] = (int) $meta['video']['resolution_y'];
                    }
                    if (! empty($meta['playtime_seconds'])) {
                        $info['duration_seconds'] = (float) $meta['playtime_seconds'];
                    }
                    if (! empty($meta['video']['frame_rate'])) {
                        $info['frame_rate'] = $meta['video']['frame_rate'];
                    }
                } catch (\Exception $e) {
                    $info['error'] = 'getID3 failed: ' . $e->getMessage();
                }
            }

            $items['files'][] = $info;
        }

        return $items;
    }

    private function countFilesAndFolders(string $path): array
    {
        $files = 0;
        $folders = 0;

        if (! is_dir($path)) {
            return ['files' => 0, 'folders' => 0];
        }

        $iterator = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $folders++;
            } elseif ($item->isFile()) {
                $files++;
            }
        }

        return compact('files', 'folders');
    }

    public function makePreviewFiles($path, $files)
    {
        $relative_path = Str::after($path, config('mediamanager.path'));
        $thumb_path = public_path(config('mediamanager.path')  . $relative_path . '/_thumbs');

        if (! File::exists($thumb_path)) {
            File::makeDirectory($thumb_path, 0755, true);
        }

        $preview_files = [];
        foreach ($files as $file) {
            if (in_array(strtolower($file['extension']), ['jpeg', 'jpg', 'png', 'gif', 'webp', 'avif'])) {

                $save_path = $thumb_path . '/' . strtolower($file['name']);

                if (! file_exists($save_path)) {
                    $image = Image::useImageDriver(ImageDriver::Gd)->loadFile(public_path($file['path']))->height(150)->save($save_path);
                }
                if ($path == '') {
                    $original_path = config('mediamanager.path') . '/' . $file['name'];
                } else {
                    $original_path = $path . '/' . $file['name'];
                }
                $relative_path = config('mediamanager.path') . Str::after($save_path, config('mediamanager.path'));

                $thumb_file = new \Symfony\Component\HttpFoundation\File\File($save_path);

                $fullPath = $thumb_file->getPathname();
                $mimeType = File::mimeType($fullPath);
                $info = [
                    'type' => 'file',
                    'name' => $thumb_file->getFilename(),
                    'path' => $relative_path,
                    'original_path' => $original_path,
                    'size' => $thumb_file->getSize(),
                    'modified' => Carbon::createFromTimestamp($thumb_file->getMTime())->toDateTimeString(),
                    'extension' => strtolower($thumb_file->getExtension()),
                    'mime_type' => $mimeType,
                ];

                // Image resolution
                if (str_starts_with($mimeType, 'image/')) {
                    if ($dimensions = @getimagesize($fullPath)) {
                        $info['width'] = $dimensions[0];
                        $info['height'] = $dimensions[1];
                    }
                }

                $preview_files[] = $info;
            }
        }

        return $preview_files;
    }

    public function upload($request, $path = '')
    {
        file_put_contents(public_path("$path/temp.part"), '');
    }

    public function uploadPatch($request, $path = '', $new_name = null, $fit = null)
    {

        $part_name = 'temp.part';
        $filename = strtolower($request->header('Upload-Name'));
        $offset = (int) $request->header('Upload-Offset');

        $target = public_path("$path/$part_name");

        // Chunk anhängen
        file_put_contents($target, $request->getContent(), FILE_APPEND);

        $newSize = filesize($target);
        $expectedSize = (int) $request->header('Upload-Length');

        // Wenn Upload fertig
        if ($expectedSize && $newSize === $expectedSize) {

            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);

            // Fallback für fehlende Extension
            if (! $ext) {
                $ext = 'bin'; // oder jpg, png, etc.
            }

            $finalName = $name . '.' . $ext;

            $finalPath = strtolower(public_path("$path/$finalName"));

            // rename() kann scheitern, also Fehler prüfen
            if (! rename($target, $finalPath)) {
                info("Rename failed: $target → $finalPath");
            }

            $fullPath = public_path($path . '/' . $finalName);

            // 2. SplFileInfo erzeugen
            $file = new \SplFileInfo($fullPath);

            // 3. Dateiinformationen extrahieren
            $normalizePath = fn ($path) => str_replace(public_path(), '', $path);

            $info = [
                'type' => 'file',
                'name' => $file->getFilename(),
                'path' => $normalizePath($fullPath),
                'size' => $file->getSize(),
                'modified' => Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
                'extension' => strtolower($file->getExtension()),
                'mime_type' => File::mimeType($fullPath),
            ];

            $files = [];
            $files[] = $info;

            $this->makePreviewFiles($path, $files);
        }

        return $newSize;
    }

    public function uploadDelete($request, $path)
    {
        $filename = $request->header('Upload-Name');

        $file = public_path("$path/temp.part");

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function uploadGet($request, $path)
    {
        $filename = $request->header('Upload-Name');
        $file = public_path("$path/temp.part");

        $offset = file_exists($file) ? filesize($file) : 0;

        return $offset;
    }

    public function destroyFiles($path, $files)
    {
        foreach ($files as $file) {
            $file_path = public_path($path . '/' . $file);
            $thumb_path = public_path($path . '/_thumbs/' . $file);

            if (File::exists($file_path)) {
                File::delete($file_path);
            }

            if (File::exists($thumb_path)) {
                File::delete($thumb_path);
            }
        }
    }

    public function rename($path, $new_path, $current_filename, $new_filename)
    {
        if (! $path || $path == 'NULL') {
            $path = config('mediamanager.path');
        }

        if (! $new_path || $path == 'NULL') {
            $new_path = config('mediamanager.path');
        }

        $stringService = new StringService();
        $new_filename = $stringService->sanitizeFilename($new_filename);

        $from = public_path($path . '/' . $current_filename);
        $to = public_path($new_path . '/' . $new_filename);

        $from_thumbs = public_path($path . '/_thumbs/' . $current_filename);
        $to_thumbs = public_path($new_path . '/_thumbs/' . $new_filename);

        if (file_exists($from)) {
            rename($from, $to);
        }
        if (file_exists($from_thumbs)) {
            rename($from_thumbs, $to_thumbs);
        }
    }
}
