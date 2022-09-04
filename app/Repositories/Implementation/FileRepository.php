<?php
namespace App\Repositories\Implementation;
use App\Helpers;
use App\Repositories\IFileRepository;
use App\Models\File;

class FileRepository implements IFileRepository{
    public function create($fileDto){
        $file = new File();
        $file-> fileID = $fileDto->fileID;
        $file-> timesDownloaded = 0;
        $file-> deleteAt = $fileDto->deleteAt;
        $file-> uploadUser = $fileDto->uploadUser;
        $file-> uploadIp = $fileDto->uploadIp;
        $file-> userName = $fileDto-> userName;
        $file->fileName = $fileDto-> fileName;
        $file->save();
        
    }
    public function findById($id){
        return File::findOrFail($id);

    }
    public function findByFileId($fileId){
        return File::where('fileID', $fileId)->firstOrFail();
    }
    public function getAll(){
        $files = File::all()->map(function($file){
            $file -> deleteAt = Helpers::getReadableTime($file->deleteAt);
            return $file;
        });
        return $files;
    }
    public function updateDownloaded($file){
        $file = File::findOrFail($file->id);
        $file->timesDownloaded = $file->timesDownloaded + 1;
        $file->save();
    }
    public function isValidForDownload($id){
        $file = File::findOrFail($id);
        if($file->deleteAt == -1){
            return true;
        }
        if($file->deleteAt == 0){
            return true;
        }
        if($file->deleteAt < now()->timestamp){
            return false;
        }
        return true;
    }

    public function delete($file){
        $file->delete();
    }

    public function getUsersUploadedFiles($user){
        $files = File::where('uploadUser',$user->email )->get()->map(function($file){
            $file -> deleteAt = Helpers::getReadableTime($file->deleteAt);
            return $file;
        });
        return $files;
    }
    public function fileBelongsToUser($user, $file){
        $file = File::where([['uploadUser',$user->email],
                              ['id',$file->id]])->get();
        if(exists($file)){
            return true;
        }
        return false;
    }
}
?>