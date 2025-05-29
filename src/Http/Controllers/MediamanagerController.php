<?php


namespace Itstudioat\Mediamanager\src\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class MediamanagerController extends Controller
{

    public function folderStructure(Request $request)
    {
        $path = $request->query('path');
        return response()->json($path, 200);
    }
}
