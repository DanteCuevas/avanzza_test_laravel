<?php

namespace App\Services\File;

Class FileService {

    private $prefix = 'upload_';
    private $dir;

    public function __construct() 
    {
        $this->dir = 'public/'.env('FILES_DIR');
    }

    public function upload($file)
    {

        $fileName = $this->getUniqueFileName() .'.'. $file->extension();
        $response = $file->storeAs($this->dir, $fileName);
        return $fileName;

    }

    public function setPrefix($prefix): FileService
    {
        $this->prefix = $prefix.'_';
        return $this;
    }

    public function setDir($dir): FileService
    {
        $this->dir = 'public/'.$dir;
        return $this;
    }

    private function getUniqueFileName()
    {
        return $this->prefix . $this->getUserId() . $this->getUniqueTime();
    }

    private function getUniqueTime()
    {
       return now()->timestamp . '.' . now()->milli;
    }

    private function getUserId()
    {
       return auth()->user() ? auth()->user()->id : '';
    }

}