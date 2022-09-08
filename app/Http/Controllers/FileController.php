<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Repositories\IBlackListedFilesRepository;
use App\Repositories\IFileFragmentRepository;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use ZipArchive;
use \App\Models\File;
use App\Repositories\IFileRepository;
use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\Auth;

class FileController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private IFileRepository $fileRepository;
    private IUserRepository $userRepository;
    private IBlackListedFilesRepository $blackListedFilesRepository;
    private IFileFragmentRepository $fileFragmentRepository;
    function __construct(IFileRepository $fileRepo, IUserRepository $userRepo, IBlackListedFilesRepository $blackListedFilesRepo, IFileFragmentRepository $fileFragmentRepo)
    {
        $this->fileRepository = $fileRepo;
        $this->userRepository = $userRepo;
        $this->blackListedFilesRepository = $blackListedFilesRepo;
        $this->fileFragmentRepository = $fileFragmentRepo;
    }



    public function fetchFilesForUser($userId){
        $user = $this->userRepository->getUserById($userId);
        $files = $this->fileRepository->getUsersUploadedFiles($user);
        return response()->json($files);

    }

    public function deleteFile($fileId)
    {
        if ($fileId == null) {
            return view('error',['message'=>'You must enter a file Id.']);
        }

        $file = $this->fileRepository->findById($fileId);
        $user = $this->userRepository->getUserByEmail(Auth::user()->email);
        if($file->uploadUser != $user->email){
            return view('error',['message'=>'File does not exist.']);

        }
        if (!$file->exists()) {
            return view('error',['message'=>'File does not exist.']);

        }
        $this->fileRepository->delete($file);
        return response(200);
    }

    public function listFiles()
    {
        $files = $this->fileRepository->getAll();
        return view('tables/listUploadedFiles', ['files'=>$files]);
    }


    public function fetchFile($fileId)
    {
        $fileDB = $this->fileRepository->findByFileId($fileId);
        if (!$fileDB) {
            return view('error',['message'=>'File does not exist.']);

        }

        if ($fileDB->deleteAt < now()->timestamp && $fileDB->deleteAt != 0 && $fileDB->deleteAt != -1) {
            return view('error',['message'=>'File does not exist.']);

        }
        $resp = response()->download($fileDB->fileName, $fileDB->userName, ['Content-Type: application/octet-stream']);
        if ($fileDB->deleteAt == 0) {
            $fileDB->delete();
            return $resp;
        }
        $this->fileRepository->updateDownloaded($fileDB);
        return $resp;
    }

    public function displayFile($fileId)
    {
        $fileDB = $this->fileRepository->findByFileId($fileId);
        if (!$fileDB->exists()) {
            return view('error',['message'=>'File does not exist.']);

        }
        return view('downloadFile',['fileName'=>$fileDB->userName, 'fileSize'=>"50Mb", 'fileId' =>$fileDB->fileID]);
    }

    public function upload(Request  $request)
    {
        if (($request->file('files')) == null) {
            return view('error',['message'=>'You must upload at least one file.']);

        }
        $size = 0;
        foreach ($request->file('files') as $fileFragment){
           $size = $size +$fileFragment->getSize();
        }
        $size = $size / 1024 / 1024;
        // if(Auth::user() && $size > 100){
        //     return view('error',['message'=>'Logged in users can upload files up to 100Mb in size.']);

        // }
        // if (!Auth::user() && $size > 20){
        //     return view('error',['message'=>'Guests can upload files up to 20 Mb in size.']);

        // }
        $zip_file = tempnam("/tmp", uniqid());
        $zip = new ZipArchive();
        $file = new File();
        $file->fileName = $zip_file;
        $fileId = uniqid();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($request->input('encrypt') == "yes") {
            
            $password = uniqid();
            $zip->setPassword($password);
        } else {
            $password = "";
        }
        

        foreach ($request->file('files') as $fileFragment) {
            if ($this->blackListedFilesRepository->isBlacklisted($fileFragment)) {
                return view('error',['message'=>'You tried uploading a blacklisted file. '.$fileFragment->getClientOriginalName()]);

            }

            $this->fileFragmentRepository->createFragment($fileFragment, $fileId);

            $zip->addFile($fileFragment->getPathname(), $fileFragment->getClientOriginalName());
            if ($request->input('encrypt') == "yes") {
                $zip->setEncryptionName($fileFragment->getClientOriginalName(), ZipArchive::EM_AES_256, $password);
            }

        }
        
        $zip->close();
        $file->userName = $request->input('uploadName')??'upload' . ".zip";
        $file->uploadUser = Auth::user()->email??'UPLOADED_BY_GUEST';
        $file->uploadIp = $request->ip();
        $file->deleteAt = Helpers::getDownloadEpoch($request->input('delete'));
        $file->timesDownloaded = 0;
        $file->fileId = $fileId;
        $file->save();

        $userId=null;
        if(!!Auth::user())
        {
            $userId = $this->userRepository->getUserByEmail(Auth::user()->email)->id;
        }
        if ($request->input('encrypt') == "yes") {
            return view('downloadFile',[
                'fileName'=>$file->userName,
                'fileId'=>$file->fileId,
                'password'=>$password,
                'fileSize'=>'50MB',
                'uploadUser' =>$file->uploadUser,
                'uploadId'=> $userId
            ]);
        }
        return view('downloadFile',[
            'fileName'=>$file->userName,
            'fileId'=>$file->fileId,
            'fileSize'=>'50MB',
            'uploadUser' =>$file->uploadUser,
            'uploadId'=> $userId
        ]);

    }
}
