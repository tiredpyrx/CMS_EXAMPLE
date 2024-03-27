<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self content()
 * @method static self path()
 * @method static self extension()
 */
final class PageBladeTemplate extends Enum
{
    public static function values(): array
    {
        return [
            'content' => <<<BLADE
            @extends('front.pages.template.index')

            @section('page')

            @endsection

            BLADE,
            'path' => 'resources/views/front/pages/',
            'extension' => '.blade.php'
        ];
    }
}
