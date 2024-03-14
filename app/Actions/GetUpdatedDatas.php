<?php

namespace App\Actions;


class GetUpdatedDatas
{
    public function execute(array $safeRequest, string $modelName, int $modelId)
    {
        $originalAttributes = getModel($modelName)::find($modelId)->getAttributes();
        $result = array_diff_assoc($safeRequest, $originalAttributes);
        $bools = collect($safeRequest)->filter(fn($val) => is_bool($val));
        $bools->each(fn($bool, $key) => $result[$key] = $bool);
        return $result;
    }
}
