<?php
namespace App\Repositories;

interface IUserRepository {
    function getUserByID($id);
    function getUserByEmail($email);
    function getAll();
}


?>