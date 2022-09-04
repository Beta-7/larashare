<?php

namespace App\Http\Controllers;

use App\Models\BlacklistedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Implementation\BlackListedFilesRepository;
class BlackListedFileController extends Controller
{
    private BlackListedFilesRepository $blacklistedfilesRepository;
    public function __construct(BlackListedFilesRepository $blacklistedfilesRepo){
    $this->blacklistedfilesRepository=$blacklistedfilesRepo;
    }
    public function blacklist(Request $request)
    {
        if (!Auth::user() || !(Auth::user()->role == "moderator" || Auth::user()->role == "admin")) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        if (($request->file('files')) == null) {
            return response()->json([
                'message' => 'You must upload at least one file.'
            ], 422);
        }
        foreach ($request->file('files') as $file) {
            $blacklistedFile = new BlacklistedFile();
            $blacklistedFile->reason = $request->input('reason');
            $blacklistedFile->md5hash = md5_file($file->getPathname());
            $blacklistedFile->blacklistedBy = "dsa";
            $blacklistedFile->save();
        }
        return response()->json([
            'message' => 'Blacklisted ' . count($request->file('files')) . ' files for ' . $request->input('reason')
        ], 201);
    }
    public function getBlackListedFiles(){
        $files = $this->blacklistedfilesRepository->getBlacklistedfiles();
        return view('tables/listBlacklistedFiles', ['files' => $files]);
    }

    public function deleteBlacklistedFile($id){
        $file = $this->blacklistedfilesRepository->getFileById($id);
        $this->blacklistedfilesRepository->deleteFile($file);
        return view ('tables/listBlacklistedFiles');
    }
}
