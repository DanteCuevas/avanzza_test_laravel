<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\File\FileRequest;
use App\Http\Requests\File\MultipleFileRequest;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\File\FileCollection;
use App\Http\Resources\File\MultipleFileCollection;
use App\Models\File;
use App\Services\File\FileService;
use App\Services\File\MultipleFileService;
use App\Actions\File\MultipleFileAction;
use App\Actions\File\DeleteFileAction;

class FileApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $files = File::orderBy('id', 'desc')
            ->with('user')->paginate(10);

        return new FileCollection($files);

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
            ], $e->getCode());

        }

    }

    public function multipleStore(MultipleFileRequest $request, MultipleFileService $multipleFileService, MultipleFileAction $multipleFileAction)
    {

        try {

            $fileNames = $multipleFileService->setPrefix('multiple_files')
                ->multipleUpload($request->multiple_files);
            $files = $multipleFileAction->save($fileNames);
            
            return response()->json( new MultipleFileCollection($files), 201);

        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => $e->getMessage()
            ], $e->getCode());

        }

    }

    public function destroy(File $file, $type, DeleteFileAction $deleteFileAction)
    {
        try {
            
            $deleteFileAction->execute($file, $type);
            return response()->json( [], 204);

        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => $e->getMessage()
            ], $e->getCode());

        }
    }
}
