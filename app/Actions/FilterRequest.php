<?php

namespace App\Actions;

use Illuminate\Http\Request;

class FilterRequest
{
    public function execute(Request $request, string $modelName)
    {
        $model = getModel($modelName);
        $allowedDatas = $model::getMassAssignables()->toArray();
        $allowedDatas = array_intersect_key($allowedDatas, $request->all());
        $allowedBools = $model::getMassAssignableBools()->toArray();
        $safeRequest = array_filter(array_keys($request->all()), fn ($d) => in_array($d, $allowedDatas) || is_null($d));
        $safeRequest = $request->only($safeRequest);
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
