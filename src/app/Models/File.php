<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;


   protected $fillable = ['updated_at','created_at','fileID','timesDownloaded','deleteAt', 'uploadUser', 'uploadIp','userName','fileName'];
}
