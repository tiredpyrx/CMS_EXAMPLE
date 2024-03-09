<?php

namespace App\Actions;


class GetUpdatedDatas
{
    public function execute(array $safeRequest, string $modelName)
    {
        $originalAttributes = getModel($modelName)->get(array_keys($safeRequest));
        return collect($safeRequest)->diff($originalAttributes)->toArray();
    }
}
