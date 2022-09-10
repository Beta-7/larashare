<?php

namespace App\Http\Controllers;

use App\Models\BlacklistedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\IBlackListedFilesRepository;
use App\Repositories\IFileRepository;
use App\Repositories\IFileFragmentRepository;

class BlackListedFileController extends Controller
{
    private IBlackListedFilesRepository $blacklistedfilesRepository;
    private IFileRepository $fileRepository;
    private IFileFragmentRepository $fileFragmentRepository;
    public function __construct(IBlackListedFilesRepository $blacklistedfilesRepo, IFileRepository $fileRepo, IFileFragmentRepository $fileFragmentRepo){
    $this->blacklistedfilesRepository=$blacklistedfilesRepo;
    $this->fileRepository = $fileRepo;
    $this->fileFragmentRepository = $fileFragmentRepo;
    }
    public function blacklist(Request $request)
    {
        if (!Auth::user() || !(Auth::user()->role == "moderator" || Auth::user()->role == "admin")) {
            return view('error',['message'=>'You don\'t have the rights to be here.']);
        }
        if (($request->file('files')) == null) {
            return view('error',['message'=>'You must upload at least one file.']);

        }
        foreach ($request->file('files') as $file) {
            $blacklistedFile = new BlacklistedFile();
            $blacklistedFile->reason = $request->input('reason');
            $blacklistedFile->md5hash = md5_file($file->getPathname());
            $blacklistedFile->blacklistedBy = "dsa";
            $blacklistedFile->save();
            $this->retroActiveBlacklist($blacklistedFile->md5hash, $request->input('reason'));
        }
        return $this->getBlackListedFiles();
    }

    private function retroActiveBlacklist($hash, $reason){
        $fileFragments = $this->fileFragmentRepository->getByHash($hash);
        foreach ($fileFragments as $fragment){
            $file = $this->fileRepository->findByFileId($fragment->fileID);
            if($file->uploadUser != "UPLOADED_BY_GUEST"){
                $details = [
                    'title'=>$file->userName,
                    'reason'=>$reason
                ];
                \Mail::to($file->uploadUser)->send(new \App\Mail\DownloadRemoved($details));
            }
            $file->delete();            

        }


    }

    public function getBlackListedFiles(){
        if (!Auth::user() || !(Auth::user()->role == "moderator" || Auth::user()->role == "admin")) {
            return view('error',['message'=>'You don\'t have the rights to be here.']);
        }
        $files = $this->blacklistedfilesRepository->getBlacklistedfiles();
        return view('tables/listBlacklistedFiles', ['files' => $files]);
    }

    public function deleteBlacklistedFile($id){
        if (!Auth::user() || !(Auth::user()->role == "moderator" || Auth::user()->role == "admin")) {
            return view('error',['message'=>'You don\'t have the rights to be here.']);
        }
        $file = $this->blacklistedfilesRepository->getFileById($id);
        $this->blacklistedfilesRepository->deleteFile($file);
        return $this->getBlackListedFiles();
    }
}
