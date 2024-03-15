<?php

namespace App\Models;

use App\Pipes\ActivityPreventAttributePipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected static $ignoreChangedAttributes = ['updated_at'];

    public const RULES = ['title' => 'required'];

    protected $fillable = [
        'user_id',
        'category_id',

        'title',
        'publish_date',

        'published',
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
            ->setDescriptionForEvent(function ($eventName) {
                $eventName = config('activitylog.EVENT_NAMES')[$eventName];
                return ":subject.title, kullanıcı :causer.name tarafından {$eventName}.";
            })
            ->logAll()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }

    public function getSlugAttribute(): string
    {
        return $this->fields()->where('handler', 'slug')->value('value');
    }

    public function field(string $handler = ''): mixed
    {
        $field = $this->fields()->where('handler', $handler)->where('active', 1);
        if ($field->exists())
            $field = $field->first();
        else return collect([]);

        $value = match ($field->type) {
            'multifield' => $field->fields()->pluck('value'),
            'siblingfield' => $field->fields()->pluck('value')->chunk(2),
            default => $field->value
        };
        return $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function blueprints()
    {
        return $this->hasMany(Blueprint::class);
    }

    public function getAuthorNameAttribute()
    {
        return User::find($this->user_id)->name;
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function getPrimaryTextAttribute()
    {
        return $this->title;
    }
}
