<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\File\FileRequest;
use App\Http\Resources\File\FileResource;
use App\Models\File;
use App\Services\File\FileService;

class FileApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request, FileService $fileService)
    {

        try {

            $input = $request->all();
            $input['file'] = $fileService->setPrefix('files')->upload($request->file);
            $file = File::create($input);
            
            return response()->json( new FileResource($file), 201 );

        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);

        }

    }

    public function destroy($id)
    {
        //
    }
}