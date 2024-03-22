<?php

namespace App\Actions;

use Illuminate\Http\Request;

class ToggleActive
{
    public function execute(Request $request, string $modelName)
    {
        $model = getModelClass($modelName)::where($request->get('primaryKey'), $request->get('primaryValue'));
        if ($model->doesntExist()) return 0;

        $model = $model->first();
        return $model->update(['active' => filter_var($request->get('checked'), FILTER_VALIDATE_BOOLEAN)]);;
    }
}
