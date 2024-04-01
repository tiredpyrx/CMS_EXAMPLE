<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self column()
 * @method static self type()
 * @method static self required()
 * @method static self url()
 * @method static self sluggable()
 * @method static self active()
 */
final class FieldDefaultValues extends Enum
{
    private const DEFAULT_COLUMN_VALUE = '6';
    private const DEFAULT_TYPE_VALUE = 'text';
    private const DEFAULT_REQUIRED_VALUE = 0;
    private const DEFAULT_SLUGABBLE_VALUE = 0;
    private const DEFAULT_URL_VALUE = 0;
    private const DEFAULT_ACTIVE_VALUE = 1;

    public static function values(): array
    {
        return [
            'column' => FieldDefaultValues::DEFAULT_COLUMN_VALUE,
            'type' => FieldDefaultValues::DEFAULT_TYPE_VALUE,
            'required' => FieldDefaultValues::DEFAULT_REQUIRED_VALUE,
            'sluggable' => FieldDefaultValues::DEFAULT_SLUGABBLE_VALUE,
            'url' => FieldDefaultValues::DEFAULT_URL_VALUE,
            'active' => FieldDefaultValues::DEFAULT_ACTIVE_VALUE,
        ];
    }
}
