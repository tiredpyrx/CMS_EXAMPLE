<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class SpecialLink extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    const SPECIAL_NAMES = ['Home'];

    protected $fillable = [
        'name',
        'link',
        'view'
    ];

}
