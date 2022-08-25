<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use ZipArchive;
use \App\Models\File;
use \App\Models\BlacklistedFile;
use Illuminate\Support\Facades\Auth;

class FileController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function blacklist(Request $request){
        foreach ($request->file('files') as $file){
            $blacklistedFile = new BlacklistedFile();
            $blacklistedFile->reason = $request->input('reason');
            $blacklistedFile->md5hash = md5_file($file->getPathname());
            $blacklistedFile->blacklistedBy="dsa";
            $blacklistedFile->save();
        }
    }


    public function fetchFile($fileId){
        $fileDB = File::where("fileId", $fileId)->first();

        if(!$fileDB->exists()){
            return response('File not found', 404);
        }

        if($fileDB->deleteAt < now()->timestamp && $fileDB->deleteAt != 0 ) {
            return response('File not found', 404);
        }
        $resp = response()->download($fileDB->fileName, $fileDB->userName, ['Content-Type: application/octet-stream']);
        if($fileDB->deleteAt == 0){
            $fileDB->delete();
            return $resp;
        }

        $fileDB->timesDownloaded = $fileDB->timesDownloaded +1;
        $fileDB->save();
        return $resp;



    }


    public function upload(Request  $request){

        if(($request->file('files'))==null){
            return response('You need to upload at least a single file', 403)->header('Content-Type', 'text/plain');
        }
        $zip_file = tempnam("/tmp", uniqid());
        $zip = new ZipArchive();

        $file = new File();
        $file->fileName=$zip_file;
        $zip->open($zip_file,ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->setPassword('test');

        foreach ( $request->file('files') as $fileFragment) {

            $hash = md5_file($fileFragment->getPathname());
            $blacklisted = BlacklistedFile::where("md5hash",$hash);

            if($blacklisted->exists()){
                return response('One or more files have been blacklisted', 403)
                    ->header('Content-Type', 'text/plain');
            }

            $zip->addFile($fileFragment->getPathname(), $fileFragment->getClientOriginalName());
            $zip->setEncryptionName($fileFragment->getClientOriginalName(), ZipArchive::EM_AES_256,'test');

        }
        $zip->close();
        $file->userName=$request->input('uploadName') . ".zip";

        if ($request->input('uploadName')==null){
            $file->userName="upload.zip";
        }
        if(Auth::user()){
            $file->uploadUser=Auth::user()->name;
        }
        else{
            $file->uploadUser="UPLOADED_BY_GUEST";
        }
        $file->uploadIp=$request->ip();
        switch($request->input('delete')){
            case "never":
                $file->deleteAt = 99999999999;
                break;
            case "delete":
                $file->deleteAt = 0;
                break;
            case 1:
            case 2:
            case 3:
            case 5:
            case 10:
            case 14:
                $file->deleteAt = now()->addDay($request->input('delete'))->timestamp;
                break;
                
        }
        $file->timesDownloaded=0;
        $file->fileId=uniqid();
        $file->save();
        return "Succesfully uploaded file";
    }
}
