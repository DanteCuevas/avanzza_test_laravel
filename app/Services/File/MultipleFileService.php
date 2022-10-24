<?php

namespace App\Services\File;

use App\Services\File\FileService;
use App\Models\File;

Class MultipleFileService extends FileService {

    public function multipleUpload($files)
    {

        $fileNames = [];
        foreach ($files as $key => $file) {

            $newFile = $this->setUnique()->upload($file['file']);
            $fileNames[] = [
                'file'      => $newFile,
                'file_name' => $file['file_name']
            ];
            
        }
        return $fileNames;

    }

}