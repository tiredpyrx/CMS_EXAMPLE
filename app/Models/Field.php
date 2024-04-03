<?php

namespace App\Models;

use App\Enums\FieldDefaultValues;
use App\Enums\FieldTypes;
use App\Traits\AdvancedModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class Field extends Model
{
    use HasFactory, SoftDeletes, AdvancedModel;

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

        'image' => 'image',
        'images' => 'images',


        'as_option' => 'as_option',
        'required' => 'required',
        'sluggable' => 'sluggable',
        'url' => 'url',
        'active' => 'active',

        'deleted_at' => 'deleted_at'
    ];

    public const MASS_ASSIGNABLE_BOOLS = [
        'as_option' => 'as_option',
        'required' => 'required',
        'sluggable' => 'sluggable',
        'url' => 'url',
        'active' => 'active',
    ];

    public const RULES = [
        'label' => ['nullable', 'string', 'max:60'],
        'handler' => ['required', 'string', 'slug:Alan işleyici ', 'max:60'],
        'value' => ['nullable', 'string'],
        'description' => ['nullable', 'string', 'max:160'],
        'type' => ['nullable', 'string'],
        'column' => ['nullable', 'string'],
        'as_option' => ['nullable', 'string'],
        'required' => ['nullable', 'string'],
        'sluggable' => ['nullable', 'string'],
        'url' => ['nullable', 'string'],
        'active' => ['nullable', 'string'],
    ];

    public const PRIMARY_HANDLERS = [
        'title',
        'slug',
        'changefreq',
        'priority'
    ];

    public const DETAILED_HANDLERS = [
        'slug',
        'changefreq',
        'priority'
    ];

    public const DETAILED_RECORDS = [
        [
            'required' => true,
            'label' => 'Slug',
            'handler' => 'slug',
            'column' => '6',
            'sluggable' => true
        ],
        [
            'required' => true,
            'value' => "0.5",
            'type' => "number",
            'min_value' => '0',
            'max_value' => '1',
            'step' => '0.1',
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

    public const TYPES_WITH_CHILDREN = [
        'multifield',
        'siblingfield',
        'select'
    ];

    protected $fillable = [
        'user_id',
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

        'as_option',
        'required',
        'url',
        'sluggable',
        'active',

        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function getMassAssignableAttributes()
    {
        return $this->getAttributesOnly(Field::getMassAssignables()->keys());
    }

    public function getAttributesOnly(array|Collection $keyArray)
    {
        if (!($keyArray instanceof Collection)) {
            $keyArray = collect($keyArray);
        }
        return collect($this->getAttributes())
            ->filter(
                fn ($d, $k) => in_array($k, array_values($keyArray->toArray()))
            )->toArray();
    }

    public function getPrimaryTextAttribute()
    {
        return $this->handler;
    }

    /**
     * @return string[]
     */
    public static function getTypes(): array
    {
        return FieldTypes::values();
    }

    /**
     * @return array[]<array-key, string>
     */
    public static function getTypesWithLabels(): array
    {
        $result = [];
        foreach (FieldTypes::values() as $value) {
            $result[] = [
                'label' => ucfirst($value),
                'value' => $value
            ];
        }

        return $result;
    }

    public static function getDefaultValues(): array
    {
        return FieldDefaultValues::values();
    }

    public static function getDefaultColumnValue(): string
    {
        return FieldDefaultValues::column();
    }

    public static function getDefaultTypeValue(): string
    {
        return FieldDefaultValues::type();
    }

    public static function getDefaultRequiredValue(): string
    {
        return FieldDefaultValues::required();
    }

    public static function getDefaultSluggableValue(): string
    {
        return FieldDefaultValues::sluggable();
    }
    
    public static function getDefaultUrlValue(): string
    {
        return FieldDefaultValues::url();
    }

    public static function getDefaultActiveValue(): string
    {
        return FieldDefaultValues::active();
    }

    public function onlyOptionFields()
    {
        return $this->fields()->where('as_option', 1)->get();
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function firstFile()
    {
        return $this->files()->count() ? $this->files()->first() : null;
    }

    public function getMediaTypes()
    {
        return FieldTypes::getMediaTypes();
    }

    public static function onlyWithTypes(array $types)
    {
        $response = Field::all()->filter(fn ($field) => in_array($field->type, $types));
        return $response->count() ? $response : false;
    }
}
