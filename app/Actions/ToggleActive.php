<?php

namespace App\Actions;

use Illuminate\Http\Request;

class ToggleActive
{
    public function execute(Request $request, string $modelName)
    {
        $getInputs = new GetInputs();
        $datas = $getInputs->execute($request, 'primaryKey', 'primaryValue', 'checked');

        $model = getModel($modelName)::where($datas['primaryKey'], $datas['primaryValue']);
        if ($model->doesntExist()) return 0;

        $model = $model->first();
        return $model->update(['active' => filter_var($datas['checked'], FILTER_VALIDATE_BOOLEAN)]);;
    }
}
