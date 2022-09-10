<?php
namespace App\Repositories;

use App\Models\FileFragment;

Interface IFileFragmentRepository {
    function createFragment($file, $fileID);
    function getByHash($hash);
}