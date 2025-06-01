<?php

namespace Itstudioat\Mediamanager\src\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Itstudioat\Mediamanager\src\Services\MediaManagerService;

class MediamanagerController extends Controller
{
    public function folderStructure(Request $request)
    {
        $path = $request->query('path');
        if (! $path) {
            $path = public_path(config('mediamanager.path'));
        } else {
            $path = public_path($path);
        }

        $mediamanagerService = new MediaManagerService();
        $structure = $mediamanagerService->folderStructure($path);
        $structure['preview_files'] = $mediamanagerService->makePreviewFiles($structure['files']);

        return response()->json($structure, 200);
    }
}
