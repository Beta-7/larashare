<?php
namespace App\Repositories\Implementation;
use App\Models\User;

class UserRepository{
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