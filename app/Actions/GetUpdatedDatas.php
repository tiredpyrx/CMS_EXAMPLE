<?php

namespace App\Actions;


class GetUpdatedDatas
{
    public function execute(array $safeRequest, string $modelName, int $modelId)
    {
        $originalAttributes = getModelClass($modelName)::find($modelId)->getAttributes();
        $result = array_diff_assoc($safeRequest, $originalAttributes);
        return $result;
    }
}
