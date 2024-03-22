<?php

namespace App\Actions;

use Illuminate\Http\Request;

class FilterRequest
{
    public function execute(Request $request, string $modelName)
    {
        $model = getModelClass($modelName);
        $allowedGeneral = $model::getMassAssignables()->toArray();
        $allowedGeneral = array_intersect_key($allowedGeneral, $request->all());
        $allowedBools = $model::getMassAssignableBools()->toArray();
        $allowedDatas = array_filter(array_keys($request->all()), fn ($d) => in_array($d, $allowedGeneral) || is_null($d));
        $safeRequest = $request->only($allowedDatas);
        foreach ($safeRequest as $key => $value) {
            if (in_array($key, $allowedBools))
                $safeRequest[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        foreach ($allowedBools as $key => $value) {
            if (!array_key_exists($key, $safeRequest)) {
                $safeRequest[$key] = false;
            }
        }
        return $safeRequest;
    }
}
