<?php

namespace App\Models;

use App\Enums\PostChangeFrequencyOptions;
use App\Pipes\ActivityPreventAttributePipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected static $ignoreChangedAttributes = ['updated_at'];

    public const DEFAULT_ACTIVE_VALUE = TRUE;

    public const RULES = ['title' => 'required|string'];

    public const MASS_ASSIGNABLES = [
        'title' => 'title',
        'publish_date' => 'publish_date',
        'published' => 'published',
        'active' => 'active',
        'deleted_at' => 'deleted_at'
    ];

    public const MASS_ASSIGNABLE_BOOLS = [
        'published' => 'published',
        'active' => 'active',
    ];

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

    public function getTitle()
    {
        return $this->title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getCategoryTitle()
    {
        return $this->category_title;
    }

    public function getCategoryTitleAttribute()
    {
        return Category::find($this->category_id)->title;
    }

    public function getSlugAttribute(): string|null
    {
        return $this->fields()->where('handler', 'slug')->value('value');
    }

    public function field(string $handler, mixed $default = ''): mixed
    {
        $field = $this->fields()->where('handler', $handler)->where('active', 1);
        if ($field->exists()) $field = $field->first();
        else return $default;

        $originalField = Field::where([
            ['handler', $handler],
            ['id', '!=', $field->id],
            ['category_id', '!=' ,null],
            ['active', 1]
        ])->first();

        $value = match ($field->type) {
            'multifield' => $field->fields()->pluck('value'),
            'siblingfield' => array_chunk($field->fields()->pluck('value')->toArray(), 2),
            'image' => collect($field->files()->first()?->only(File::FRONTED_DATAS)),
            'images' => collect($field->files)->map(fn($file) => $file->only(File::FRONTED_DATAS)),
            default => $field->value
        };

        if ($field->prefix) $value = $field->prefix . $value;
        if ($field->suffix) $value .= $field->suffix;

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

    /**
     * @return string[]
     */
    public static function getChangeFrequencyValues(): array
    {
        return PostChangeFrequencyOptions::values();
    }

    public static function getChangeFrequencyAlwaysValue(): string
    {
        return PostChangeFrequencyOptions::always();
    }

    public static function getChangeFrequencyHourlyValue(): string
    {
        return PostChangeFrequencyOptions::hourly();
    }

    public static function getChangeFrequencyDailyValue(): string
    {
        return PostChangeFrequencyOptions::daily();
    }

    public static function getChangeFrequencyWeeklyValue(): string
    {
        return PostChangeFrequencyOptions::weekly();
    }

    public static function getChangeFrequencyMonthlyValue(): string
    {
        return PostChangeFrequencyOptions::monthly();
    }

    public static function getChangeFrequencyYearlyValue(): string
    {
        return PostChangeFrequencyOptions::yearly();
    }

    public static function getChangeFrequencyNeverValue(): string
    {
        return PostChangeFrequencyOptions::never();
    }

    public static function getMassAssignables()
    {
        return collect(Post::MASS_ASSIGNABLES);
    }

    public static function getMassAssignableBools()
    {
        $bools = Post::MASS_ASSIGNABLE_BOOLS;
        return Post::getMassAssignables()->filter(fn ($d) => in_array($d, $bools));
    }

    public function getFieldsWhenTypes(array $types): Collection|array
    {
        $response = $this->getActiveFields()->filter(fn($field) => in_array($field->type, $types));
        return $response->count() ? $response : [];
    }

    public function getActiveFields(): Collection
    {
        return collect(Field::where('post_id', $this->id)->getActives());
    }
}
