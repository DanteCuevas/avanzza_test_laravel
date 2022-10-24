<?php

namespace App\Actions\File;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

Class DeleteFileAction {

    public function execute(File $file, string $type)
    {

        switch ($type) {
            case 'normal':
                Storage::disk('public')->delete($file->file);
                $file->delete();
                break;
            case 'logical':
                Storage::disk('public')->move($file->file, $this->newName($file->file));
                $file->delete();
                break;
            case 'physical':
                Storage::disk('public')->delete($file->file);
                $file->file_exist = false;
                $file->save();
                break;
        }

        return true;

    }

    public function newName($name)
    {
        return str_replace('/', '/deleted.', $name);
    }

}