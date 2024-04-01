<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait AdvancedModel {
    public static function getMassAssignables() {
        $class = self::getClass();
        return collect(constant("$class::MASS_ASSIGNABLES"));
    }

    public static function getMassAssignableBools()
    {
        $class = self::getClass();
        $bools = constant("$class::MASS_ASSIGNABLE_BOOLS");
        return $class::getMassAssignables()->filter(fn ($d) => in_array($d, $bools));
    }

    private static function getClass() {
        return self::class;
    }
}