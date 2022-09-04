<?php
namespace App\Repositories;

interface IFileRepository {
    public function create($fileDto);
    public function findById($id);
    public function getAll();
    public function updateDownloaded($id);
    public function isValidForDownload($id);
    public function getUsersUploadedFiles($userDto);
}


?>