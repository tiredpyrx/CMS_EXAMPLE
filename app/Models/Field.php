<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    public const DEFAULT_COLUMN_VALUE = 6;
    public const DEFAULT_TYPE_VALUE = 'text';
    public const DEFAULT_REQUIRED_VALUE = false;
    public const DEFAULT_ACTIVE_VALUE = true;

    public const MASS_ASSIGNABLES = [
        'label' => 'label',
        'placeholder' => 'placeholder',
        'column' => 'column',
        'description' => 'description',
        'handler' => 'handler',
        'value' => 'value',
        'type' => 'type',

        'required' => 'required',
        'active' => 'active',

        'deleted_at' => 'deleted_at'
    ];

    public const MASS_ASSIGNABLE_BOOLS = [
        'required' => 'required',
        'active' => 'active',
    ];

    public const RULES = [
        'label' => ['nullable', 'string', 'max:60'],
        'handler' => ['required', 'string', 'max:60'],
        'value' => ['nullable', 'string'],
        'placeholder' => ['nullable', 'string'],
        'description' => ['nullable', 'string', 'max:160'],
        'type' => ['nullable', 'string'],
        'column' => ['nullable', 'string', 'max: 2'],
        'required' => ['nullable', 'string'],
        'active' => ['nullable', 'string'],
    ];

    public const PRIMARY_HANDLERS = [
        'title',
        'slug'
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

        'required',
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

    public function getMassAssignableBools()
    {
        return $this->getMassAssignables()->filter(fn ($d) => in_array($d, $this::MASS_ASSIGNABLE_BOOLS));
    }

    public function getPrimaryTextAttribute()
    {
        return $this->handler;
    }
}
