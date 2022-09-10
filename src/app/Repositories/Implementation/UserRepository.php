<?php
namespace App\Repositories\Implementation;
use App\Models\User;
use App\Repositories\IUserRepository;

class UserRepository implements IUserRepository{
    function getUserByID($id){
        return User::where('id',$id)->firstOrFail();
    }
    function getUserByEmail($email){
        return User::where('email', $email)->firstOrFail();
    }
    function getAll(){
        return User::orderBy('id', 'DESC')->get();
    }
}
?>