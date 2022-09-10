<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistedFile extends Model
{
    use HasFactory;
    protected $fillable = ['reason','md5hash','blacklistedBy'];
    protected $hidden = ['id'];

}