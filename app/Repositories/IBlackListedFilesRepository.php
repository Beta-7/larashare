<?php
namespace App\Repositories;

use \App\Models\BlacklistedFile;

interface IBlackListedFilesRepository {

 function getBlacklistedfiles();
 function deleteFile($file);
 function getFileById($id);
 function isBlacklisted($fileFragment);
}
