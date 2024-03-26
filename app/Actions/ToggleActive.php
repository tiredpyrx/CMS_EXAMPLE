<?php

namespace App\Actions;

use App\Models\Category;
use Illuminate\Http\Request;

class ToggleActive
{
    public function execute(Request $request, string $modelName)
    {
        $model = getModelClass($modelName)::where($request->get('primaryKey'), $request->get('primaryValue'));
        if ($model->doesntExist()) return 0;

        $model = $model->first();
        if (strtolower($modelName) == 'field') {
            $replicatedPostField = $model->category->posts->each(fn ($post) => $post->fields()->where('handler', $model->handler))->first();
            $this->update($replicatedPostField, $request);
        }
        return $this->update($model, $request);
    }

    public function update($model, $request)
    {
        $model->update(['active' => filter_var($request->input('checked'), FILTER_VALIDATE_BOOLEAN)]);
        return $model;
    }
}
