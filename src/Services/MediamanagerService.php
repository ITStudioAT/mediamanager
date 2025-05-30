<?php

namespace Itstudioat\Mediamanager\src\Services;

use Carbon\Carbon;
use FilesystemIterator;
use getID3;
use Illuminate\Support\Facades\File;

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
            $fullPath = $file->getPathname();
            $mimeType = File::mimeType($fullPath);
            $info = [
                'type' => 'file',
                'name' => $file->getFilename(),
                'path' => $normalizePath($fullPath),
                'size' => $file->getSize(),
                'modified' => Carbon::createFromTimestamp($file->getMTime())->toDateTimeString(),
                'extension' => $file->getExtension(),
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
}
