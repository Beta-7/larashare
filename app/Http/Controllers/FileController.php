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
use Illuminate\Support\Str;

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

    public function upload(Request  $request){

        $zip_file = 'data/' . Str::uuid() . ".zip";
        $zip = new ZipArchive();
        $zip->open($zip_file,ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ( $request->file('files') as $file) {
            $hash = md5_file($file->getPathname());
            $blacklisted = BlacklistedFile::where("md5hash",$hash);

            if($blacklisted->exists()){
                return response('One or more files have been blacklisted', 403)
                    ->header('Content-Type', 'text/plain');
            }

            $zip->addFile($file->getPathname(), $file->getClientOriginalName());
        }
//        if($request->input('encrypt')=="yes"){
//
//                $zip->setPassword("asd");
//
//        }
        $zip->close();
        $file = new File();
        $file->fileName=$zip_file;
        $file->userName=$request->input('uploadName') . ".zip";
        if ($request->input('uploadName')==null){
            $file->userName="upload.zip";
        }
        $file->uploadUser="aaa";
        $file->uploadIp=$request->ip();
        $file->save();
        return "asd";
    }
}
