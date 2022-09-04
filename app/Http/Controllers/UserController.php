<?php

namespace App\Http\Controllers;

use App\Repositories\Implementation\FileRepository;
use App\Repositories\Implementation\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserRepository $userRepository;
    private FileRepository $fileRepository;
    function __construct(UserRepository $userRepo, FileRepository $fileRepo){
        $this->userRepository = $userRepo;
        $this->fileRepository = $fileRepo;
    }
    function getProfile($userId){
        $user = $this->userRepository->getUserByID($userId);
        $files = $this->fileRepository->getUsersUploadedFiles($user);
        return view('profile',[
            'name' => $user->name,
            'role' => $user->role,
            'files' =>$files
        ]);
    }
    function getUsers(){
        $users = $this->userRepository->getAll();
        return view('tables/listUsers', ['users'=>$users]);
    }
}
