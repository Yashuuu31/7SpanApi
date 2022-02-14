<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHobbie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hobbie_id',
    ];
}
