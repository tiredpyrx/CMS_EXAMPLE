<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self observer()
 * @method static self user()
 * @method static self editor()
 * @method static self admin()
 */
final class UserRoles extends Enum
{
    public static function values(): array
    {
        return [
            'observer' => 'observer',
            'user' => 'user',
            'editor' => 'editor',
            'admin' => 'admin',
        ];
    }
}
