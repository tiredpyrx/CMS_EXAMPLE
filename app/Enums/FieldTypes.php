<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self text()
 * @method static self number()
 * @method static self color()
 * @method static self range()
 * @method static self multifield()
 * @method static self siblingfield()
 * @method static self texteditor()
 */
final class FieldTypes extends Enum
{
    public static function values(): array
    {
        return [
            'text' => 'text',
            'number' => 'number',
            'color' => 'color',
            'range' => 'range',
            'multifield' => 'multifield',
            'siblingfield' => 'siblingfield',
            'texteditor' => 'texteditor',
            'select' => 'select',
        ];
    }
}
