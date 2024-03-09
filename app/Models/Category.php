<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public const DEFAULT_HAVE_DETAILS_VALUE = false;
    public const DEFAULT_AS_PAGE_VALUE = false;
    public const DEFAULT_ACTIVE_VALUE = true;

    public const MASS_ASSIGNABLES = [
        'title' => 'title',
        'icon' => 'icon',
        'view' => 'view',
        'description' => 'description',

        'have_details' => 'have_details',
        'as_page' => 'as_page',
        'active' => 'active',

        'deleted_at' => 'deleted_at'
    ];

    public const MASS_ASSIGNABLE_BOOLS = [
        'have_details' => 'have_details',
        'as_page' => 'as_page',
        'active' => 'active',
    ];

    public const RULES = [
        'title' => ['required', 'string', 'max:60'],
        'icon' => ['nullable', 'string', 'max:60'],
        'view' => ['nullable', 'string', 'max:60'],
        'description' => ['nullable', 'string', 'max:160'],
    ];

    protected $fillable = [
        'user_id',
        'parent_id',

        'title',
        'icon',
        'view',
        'description',

        'have_details',
        'as_page',
        'active',

        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function blueprints()
    {
        return $this->hasMany(Blueprint::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getAuthorNameAttribute()
    {
        return User::find($this->user_id)->name;
    }

    public function getPostsCountAttribute()
    {
        return Post::where('category_id', $this->id)->count();
    }

    public function getMassAssignables()
    {
        return collect($this::MASS_ASSIGNABLES);
    }

    public function getMassAssignableBools()
    {
        return $this->getMassAssignables()->filter(fn ($d) => in_array($d, $this::MASS_ASSIGNABLE_BOOLS));
    }
}
