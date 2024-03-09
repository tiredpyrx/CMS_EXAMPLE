<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    public const MASS_ASSIGNABLES = [
        'label' => 'label',
        'placeholder' => 'placeholder',
        'column' => 'column',
        'description' => 'description',
        'handler' => 'handler',
        'value' => 'value',
        'type' => 'type',
        'active' => 'active',
        'deleted_at' => 'deleted_at'
    ];

    protected $fillable = [
        'user_id',
        'blueprint_id',
        'category_id',
        'post_id',
        'label',
        'placeholder',
        'column',
        'description',
        'handler',
        'value',
        'type',
        'active',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blueprint()
    {
        return $this->belongsTo(Blueprint::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getAuthorNameAttribute()
    {
        return User::find($this->user_id)->name;
    }

    public function getMassAssignables()
    {
        return collect($this::MASS_ASSIGNABLES);
    }
}
