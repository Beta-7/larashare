<?php
namespace App\Repositories;

interface IUserRepository {
    public function create($userDto);
    public function findById($id);
    public function getAll();
    public function lockOut($userDto);
}


?>