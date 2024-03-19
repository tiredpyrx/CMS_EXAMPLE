<?php

namespace App\Enums;

use Spatie\Enum\Enum;

final class PostChangeFrequencyOptions extends Enum
{
    private const CHANGE_FREQUENCY_ALWAYS = 'always';
    private const CHANGE_FREQUENCY_HOURLY = 'hourly';
    private const CHANGE_FREQUENCY_DAILY = 'daily';
    private const CHANGE_FREQUENCY_WEEKLY = 'weekly';
    private const CHANGE_FREQUENCY_MONTHLY = 'monthly';
    private const CHANGE_FREQUENCY_YEARLY = 'yearly';
    private const CHANGE_FREQUENCY_NEVER = 'never';

    /**
     * @method static self always()
     * @method static self hourly()
     * @method static self daily()
     * @method static self weekly()
     * @method static self monthly()
     * @method static self yearly()
     * @method static self never()
     */

    public static function values(): array
    {
        return [
            'always' => PostChangeFrequencyOptions::CHANGE_FREQUENCY_ALWAYS,
            'hourly' => PostChangeFrequencyOptions::CHANGE_FREQUENCY_HOURLY,
            'daily' => PostChangeFrequencyOptions::CHANGE_FREQUENCY_DAILY,
            'weekly' => PostChangeFrequencyOptions::CHANGE_FREQUENCY_WEEKLY,
            'monthly' => PostChangeFrequencyOptions::CHANGE_FREQUENCY_MONTHLY,
            'yearly' => PostChangeFrequencyOptions::CHANGE_FREQUENCY_YEARLY,
            'never' => PostChangeFrequencyOptions::CHANGE_FREQUENCY_NEVER,
        ];
    }
}
