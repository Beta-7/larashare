<?php
namespace App\Repositories;

interface IFileRepository
{
    function create($fileDto);

    function findById($id);

    function findByFileId($fileId);

    function getAll();

    function updateDownloaded($file);

    function isValidForDownload($id);

    function delete($file);

    function getUsersUploadedFiles($user);

    function fileBelongsToUser($user, $file);

}
?>