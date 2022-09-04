<?php
namespace App\Repositories\Implementation;

use App\Models\FileFragment;

class FileFragmentRepository {
    function createFragment($file, $fileID){
        $fileFragment =new FileFragment();
        $fileFragment->fileName = $file->getClientOriginalName();
        $fileFragment->md5hash = md5_file($file->getPathname());;
        $fileFragment->fileID = $fileID;
        $fileFragment->save();
    }
}