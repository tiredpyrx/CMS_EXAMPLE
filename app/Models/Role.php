<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // ? its better to use spatie permissions to create a complex role system

    protected $fillable = [
        'name',
    ];
}
