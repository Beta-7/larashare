<?php
namespace App\Repositories\Implementation;

use \App\Models\BlacklistedFile;
use App\Repositories\IBlackListedFilesRepository;

class BlackListedFilesRepository implements IBlackListedFilesRepository{
//    public function createBlacklistedFile($file){
//
//    }
    public function getBlacklistedfiles(){
        $files = BlacklistedFile::orderBy('id', 'DESC')->get();
        return $files;
    }
    public function deleteFile($file){
        $deleted = BlacklistedFile::where('id',$file->id)->delete();
        return;
    }
    public function getFileById($id){
        $file = BlacklistedFile::where('id',$id)->firstOrFail();
        return $file;
    }
    public function isBlacklisted($fileFragment){
        $hash = md5_file($fileFragment->getPathname());
        $blacklisted = BlacklistedFile::where("md5hash", $hash);
        return $blacklisted->exists();
    }
}
