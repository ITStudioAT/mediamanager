<?php

namespace Itstudioat\Mediamanager\src\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Itstudioat\Mediamanager\src\Services\MediamanagerService;

class MediamanagerController extends Controller
{
    public function csrf(Request $request)
    {
        return response()->json(csrf_token(), 200);
    }

    public function uploadPost(Request $request)
    {
        $path = $request->query('path');
        if (! $path || $path == 'undefined') {
            $path = config('mediamanager.path');
        }
        $mediamanagerService = new MediamanagerService();
        $mediamanagerService->upload($request, $path);

        return response('', 200)->header('Upload-Offset', 0);
    }

    public function uploadPatch(Request $request)
    {

        $path = $request->query('path');
        if (! $path || $path == 'undefined') {
            $path = config('mediamanager.path');
        }
        $mediamanagerService = new MediaManagerService();
        $newSize = $mediamanagerService->uploadPatch($request, $path);

        return response('', 200)->header('Upload-Offset', trim(str_replace(["\r", "\n"], '', $newSize)));
        //return response('', 200)->header('Upload-Offset', $newSize);
    }

    public function uploadDelete(Request $request)
    {
        $path = $request->query('path');
        if (! $path || $path == 'undefined') {
            $path = config('mediamanager.path');
        }

        $mediamanagerService->uploadDelete($request, $path);

        return response('', 200);
    }

    public function uploadGet(Request $request)
    {
        $path = $request->query('path');
        if (! $path || $path == 'undefined') {
            $path = config('mediamanager.path');
        }

        $mediamanagerService->uploadGet($request, $path);

        return response('', 200)
            ->header('Upload-Offset', $offset);
    }

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

    public function destroyFiles(Request $request)
    {
        $path = $request['path'];
        $files = $request['files'];

        if (! $path) {
            $path = config('mediamanager.path');
        }

        $mediamanagerService = new MediaManagerService();
        $mediamanagerService->destroyFiles($path, $files);
    }
}
