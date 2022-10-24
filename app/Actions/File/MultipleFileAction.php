<?php

namespace App\Actions\File;

use App\Models\File;

Class MultipleFileAction {

    public function save($fileNames){

        $dataFiles = collect($fileNames)->map(function($file, $key) {
            $file['user_id'] = auth()->user()->id;
            $file['created_at'] = now()->toDateTimeString();
            $file['updated_at'] = now()->toDateTimeString();
            return $file;
        });

        $files = File::insert($dataFiles->toArray());
        return $dataFiles;

    }

}