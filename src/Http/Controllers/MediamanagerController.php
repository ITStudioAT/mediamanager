<?php

namespace Itstudioat\Mediamanager\src\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Itstudioat\Mediamanager\src\Http\Requests\SaveFilenameRequest;
use Itstudioat\Mediamanager\src\Services\MediamanagerService;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class MediamanagerController extends Controller
{
    public function csrf(Request $request)
    {
        return response()->json(csrf_token(), 200);
    }

    public function upload(Request $request)
    {
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (! $receiver->isUploaded()) {
            return response()->json(['error' => 'Upload failed'], 400);
        }

        $save = $receiver->receive();

        if ($save->isFinished()) {
            $file = $save->getFile();
            $filename = $file->getClientOriginalName();
            $path = public_path('storage/media/uploads');
            $file->move($path, $filename);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'path' => $path,
            ]);
        }

        // Return progress
        $handler = $save->handler();

        return response()->json([
            'done' => $handler->getPercentageDone(),
        ]);
    }

    public function uploadPost(Request $request)
    {
        /*
        info($request);
        $files = $request->files;
        info($request->query('files'));
        info($request->query('file'));
        info($request->file);
        info(count($files));
        */
        $path = $request->query('path');
        if (! $path || $path == 'undefined') {
            $path = config('mediamanager.path');
        }

        $mediamanagerService = new MediamanagerService();
        $mediamanagerService->upload($request, $path);

        return response('', 200)->header('Upload-Offset', 0)->header('Part-Name', 'temp1.part');
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

    public function download(Request $request)
    {
        return response()->download(public_path($request->query('file')), basename($request->query('file')));
    }

    public function saveFilename(SaveFilenameRequest $request)
    {
        $validated = $request->validated()['data'];
        $mediamanagerService = new MediaManagerService();
        $mediamanagerService->rename($validated['path'], $validated['path'], $validated['current_filename'], $validated['filename'] . '.' . $validated['extension']);
    }
}
