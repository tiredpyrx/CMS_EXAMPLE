<?php

namespace App\Models;

use App\Enums\FieldDefaultValues;
use App\Enums\FieldTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Field extends Model
{
    use HasFactory, SoftDeletes;

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


        'as_option' => 'as_option',
        'required' => 'required',
        'active' => 'active',

        'deleted_at' => 'deleted_at'
    ];

    public const MASS_ASSIGNABLE_BOOLS = [
        'as_option' => 'as_option',
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
        'as_option' => ['nullable', 'string'],
        'required' => ['nullable', 'string'],
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
            'column' => '6'
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

    public const TYPES_WITH_STEPS = [
        'number',
        'range'
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

    public static function getMassAssignables()
    {
        return collect(Field::MASS_ASSIGNABLES);
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

    public static function getMassAssignableBools()
    {
        return Field::getMassAssignables()->filter(fn ($d) => in_array($d, Field::MASS_ASSIGNABLE_BOOLS));
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

    public static function getDefaultActiveValue(): string
    {
        return FieldDefaultValues::active();
    }

    public function onlyOptionFields()
    {
        return $this->fields()->where('as_option', 1)->get();
    }
}
