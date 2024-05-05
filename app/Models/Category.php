<?php

namespace App\Models;

use App\Pipes\ActivityPreventAttributePipe;
use App\Traits\AdvancedModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Category extends Model implements Sortable
{
    use HasFactory, SoftDeletes, LogsActivity, AdvancedModel, SortableTrait;

    protected static $ignoreChangedAttributes = ['updated_at'];

    public const DEFAULT_HAVE_DETAILS_VALUE = false;
    public const DEFAULT_AS_PAGE_VALUE = false;
    public const DEFAULT_ACTIVE_VALUE = true;


    // Category titles that application needs to be more convenient
    public const SPECIAL_TITLES = ['Home Sections', 'Pages'];

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
        'title' => ['required', 'string', 'max:60', 'unique:categories,title'],
        'icon' => ['nullable', 'string', 'max:60'],
        'view' => ['nullable', 'string', 'max:60', 'slug:View'],
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

    protected static function booted()
    {
        static::addLogChange(new ActivityPreventAttributePipe);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('safe')
        ->setDescriptionForEvent(function($eventName) {
            $eventName = config('activitylog.EVENT_NAMES')[$eventName];
            return ":subject.title, kullanıcı :causer.name tarafından {$eventName}.";
        })
        ->dontLogIfAttributesChangedOnly(['updated_at'])
        ->logAll()
        ->dontSubmitEmptyLogs()
        ->logOnlyDirty();
    }

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

    public function getPrimaryTextAttribute()
    {
        return $this->title;
    }

    public static function getSpecialTitles()
    {
        return self::SPECIAL_TITLES;
    }

    public static function getSpecialCategories()
    {
        return self::ordered()->whereIn('title', self::getSpecialTitles())->get();
    }

    public static function getExceptSpecialCategories()
    {
        return self::ordered()->whereNotIn('title', self::getSpecialTitles())->where('active', 1)->get();
    }
}
