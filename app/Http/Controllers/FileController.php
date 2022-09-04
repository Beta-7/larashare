<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Repositories\Implementation\BlackListedFilesRepository;
use App\Repositories\Implementation\FileFragmentRepository;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use ZipArchive;
use \App\Models\File;
use App\Repositories\Implementation\FileRepository;
use App\Repositories\Implementation\UserRepository;
use Illuminate\Support\Facades\Auth;

class FileController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private FileRepository $fileRepository;
    private UserRepository $userRepository;
    private BlackListedFilesRepository $blackListedFilesRepository;
    private FileFragmentRepository $fileFragmentRepository;
    function __construct(FileRepository $fileRepo, UserRepository $userRepo, BlackListedFilesRepository $blackListedFilesRepo, FileFragmentRepository $fileFragmentRepo)
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
            return response()->json([
                'message' => 'You must enter a file Id.'
            ], 422);
        }

        $file = $this->fileRepository->findById($fileId);
        $user = $this->userRepository->getUserByEmail(Auth::user()->email);
        if($file->uploadUser != $user->email){
            return response()->json([
                'message'=> 'File does not exist.'
            ],401);
        }
        if (!$file->exists()) {
            return response()->json([
                'message' => 'File does not exist.'
            ], 404);
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
            return response('File not found', 404);
        }

        if ($fileDB->deleteAt < now()->timestamp && $fileDB->deleteAt != 0 && $fileDB->deleteAt != -1) {
            return response('File not found', 404);
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
            return response('File not found', 404);
        }
        return view('downloadFile',['fileName'=>$fileDB->userName, 'fileSize'=>"50Mb", 'fileId' =>$fileDB->fileID]);
    }

    public function upload(Request  $request)
    {
        if (($request->file('files')) == null) {
            return response()->json([
                'message' => 'You must upload at least one file.'
            ], 422);
        }



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
                return response()->json([
                    'message' => 'You tried uploading a blacklisted file.',
                    'filename' => $fileFragment->getClientOriginalName()
                ], 422);
            }

            $this->fileFragmentRepository->createFragment($fileFragment, $fileId);

            $zip->addFile($fileFragment->getPathname(), $fileFragment->getClientOriginalName());
            if ($request->input('encrypt') == "yes") {
                $zip->setEncryptionName($fileFragment->getClientOriginalName(), ZipArchive::EM_AES_256, 'test');
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

        return response()->json([
            'message' => 'Succesfully uploaded file.',
            'downloadLink' => 'http://localhost/fetchFile/' . $file->fileId,
            'password' => $password
        ], 201);
    }
}
