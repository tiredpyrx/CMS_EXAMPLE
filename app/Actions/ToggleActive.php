<?php

namespace App\Actions;

class ToggleActive
{
    public function execute(mixed $primaryKey, mixed $primaryValue, string $modelName, mixed $checked)
    {
        $model = getModel($modelName)::where($primaryKey, $primaryValue);
        if ($model->doesntExist()) return 0;
        $model = $model->first();
        return $model->update(['active' => filter_var($checked, FILTER_VALIDATE_BOOLEAN)]);;
    }
}
