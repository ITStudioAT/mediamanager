<?php

namespace Itstudioat\Mediamanager\src\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Itstudioat\Mediamanager\src\Services\MediaManagerService;

class MediamanagerController extends Controller
{
    public function folderStructure(Request $request)
    {
        $base_path = $request->query('path');
        if (! $base_path) {
            $path = public_path(config('mediamanager.path'));
        } else {
            $path = public_path($base_path);
        }

        $mediamanagerService = new MediaManagerService();
        $structure = $mediamanagerService->folderStructure($path);

        return response()->json($structure, 200);
    }

    public function createPreview(Request $request)
    {
        $base_path = $request->query('path');
        if (! $base_path) {
            $path = public_path(config('mediamanager.path'));
        } else {
            $path = public_path($base_path);
        }

        $mediamanagerService = new MediaManagerService();
        $structure = $mediamanagerService->folderStructure($path);
        $preview_files = $mediamanagerService->makePreviewFiles($base_path, $structure['files']);

        return response()->json($preview_files, 200);
    }
}
