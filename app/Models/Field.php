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
        'description' => 'description',
        'handler' => 'handler',
        'value' => 'value',
        'type' => 'type',
        'min_value' => 'min_value',
        'max_value' => 'max_value',
        'step' => 'step',
        'prefix' => 'prefix',
        'suffix' => 'suffix',
        'column' => 'column',


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
        'min_value' => ['nullable'],
        'max_value' => ['nullable'],
        'step' => ['nullable'],
        'prefix' => ['string', 'nullable'],
        'suffix' => ['string', 'nullable'],
        'column' => ['nullable', 'string', 'max: 2'],
        'required' => ['nullable', 'string'],
        'active' => ['nullable', 'string'],
    ];

    public const PRIMARY_HANDLERS = [
        'title',
        'slug',
        'changefreq',
        'priority'
    ];

    public const HAVE_DETAILS_RECORDS = [
        [
            'required' => true,
            'label' => 'Slug',
            'handler' => 'slug',
            'column' => '6'
        ],
        [
            'required' => true,
            'value' => "0.5",
            'type' => "number",
            'min_value' => '0',
            'max_value' => '1',
            'step' => '0.10',
            'label' => 'Sitemap Öncelik',
            'handler' => 'priority',
            'column' => '6'
        ],
        [
            'required' => true,
            'value' => "daily",
            'label' => 'Sitemap Güncelleme Sıklığı',
            'handler' => 'changefreq',
            'column' => '6'
        ]
    ];

    public const AS_PAGE_RECORDS = [];

    protected $fillable = [
        'user_id',
        'blueprint_id',
        'category_id',
        'post_id',
        'field_id',

        'column',

        'label',
        'placeholder',
        'description',

        'handler',
        'type',
        'value',

        'min_value',
        'max_value',

        'step',

        'prefix',
        'suffix',

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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
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
