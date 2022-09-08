<?php

namespace App\Http\Controllers;

use App\Repositories\Implementation\FileRepository;
use App\Repositories\Implementation\UserRepository;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserRepository $userRepository;
    private FileRepository $fileRepository;
    function __construct(UserRepository $userRepo, FileRepository $fileRepo){
        $this->userRepository = $userRepo;
        $this->fileRepository = $fileRepo;
    }
    function changeRole($userId, $role){
        if (!Auth::user() || Auth::user()->role == "admin") {
            return view('error',['message'=>'You don\'t have the rights to be here.']);
        }
        if($userId == Auth::user()->id){
            return $this->getProfile($userId);
        }
        $user = $this->userRepository->getUserByID($userId);
        switch ($role) {
            case "admin":
                $user->role = "admin";
                break;
            case "moderator":
                $user->role = "moderator";
                break;
            case "user":
                $user->role = "user";
                break;
        }
        $user->save();
        return $this->getProfile($userId);
    }
    function getProfile($userId){
        $user = $this->userRepository->getUserByID($userId);
        $files = $this->fileRepository->getUsersUploadedFiles($user);
        return view('profile',[
            'email'=> $user->email,
            'name' => $user->name,
            'role' => $user->role,
            'files' =>$files,
            'reportId'=>$userId
        ]);
    }
    function getUsers(){
        $users = $this->userRepository->getAll();
        return view('tables/listUsers', ['users'=>$users]);
    }
    function reportUser($reportId){
        if (!Auth::user() ) {
            return view('error',['message'=>'You must be logged in to report users.']);
        }
        if($reportId == Auth::user()->id){
            return $this->getProfile($reportId);
        }
        $reported = $this->userRepository->getUserByID($reportId);
        $details = [
            'reportee'=>Auth::user()->email,
            'reported'=>$reported->email
        ];

        \Mail::to('admin@larashare.com')->send(new \App\Mail\ReportedUser($details));

        return $this->getProfile($reportId);
    }
    function emailUser(){

    }
}
