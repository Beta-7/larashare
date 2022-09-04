<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileFragment extends Model
{
    use HasFactory;
    protected $fillable = ['fileName','md5hash','fileID', 'created_at', 'updated_at'];
    protected $hidden = ['id'];

}