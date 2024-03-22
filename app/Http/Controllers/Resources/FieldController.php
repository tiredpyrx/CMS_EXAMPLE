<?php

namespace App\Http\Controllers\Resources;

use App\Actions\FilterRequest;
use App\Actions\GetUpdatedDatas;
use App\Actions\ToggleActive;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldActiveRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use App\Services\FieldService;

class FieldController extends Controller
{

    private $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    public function index()
    {
        //
    }

    public function create(string $modelName, int $modelId)
    {
        $instance = getModelClass($modelName);
        $model = $instance->find($modelId);
        $defaultColumnValue = Field::getDefaultColumnValue();
        $defaultTypeValue = Field::getDefaultTypeValue();
        $typesWithLabels = Field::getTypesWithLabels();
        return view('admin.pages.resources.field.create.index', compact('model', 'typesWithLabels', 'defaultTypeValue', 'defaultColumnValue'));
    }

    public function store(StoreFieldRequest $request, string $modelName, int $modelId, FilterRequest $filterRequest)
    {
        $instance = getModelClass($modelName);
        $model = $instance->find($modelId);
        $filtered = $filterRequest->execute($request, 'field');
        $this->fieldService->create($filtered, $model);
        $modelName = match ($modelName) {
            'category' => 'categories',
            'blueprint' => 'blueprints'
        };
        return to_route($modelName . '.edit', $model->id)->with('success', 'Alan başarıyla eklendi!');
    }

    public function show(Field $field)
    {
        $infos = $field->getMassAssignableAttributes();
        $infos = array_filter($infos, fn($d) => $d);
        $infos = array_combine(
            array_map('ucfirst', array_keys($infos)),
            array_values($infos)
        );
        return view('admin.pages.resources.field.show.index', compact('field', 'infos'));
    }

    public function edit(Field $field)
    {
        $category = $field->category;
        return view('admin.pages.resources.field.edit.index', compact('field', 'category'));
    }

    public function update(UpdateFieldRequest $request, Field $field, FilterRequest $filterRequest, GetUpdatedDatas $getUpdatedDatas)
    {
        $safeRequest = $filterRequest->execute($request, 'field');
        $success = $this->fieldService->updateFields($field, $safeRequest);
        if (!$success)
            return back()->with('error', 'Bir şeyler ters gitti!');
        return to_route('categories.edit', $field->category->id)->with('success', 'Alan başarıyla güncellendi!');
    }

    public function destroy(Field $field): bool
    {
        $field->fields()->each(fn($field) => $field->delete());
        foreach (Post::where('category_id', $field->category_id) as $post) {
            foreach (Field::where('post_id', $post->id) as $pField) {
                $pField->fields()->each(fn($field) => $field->delete());
                $pField->delete();
            }
        }
        $field->fields()->each(fn($field) => $field->delete());
        return $field->delete();
    }

    public function updateActive(UpdateFieldActiveRequest $request, ToggleActive $toggleActive, string $modelName)
    {
        $success = $toggleActive->execute($request, $modelName);

        if ($request->expectsJson() || $request->ajax())
            return 1;

        if (!$success)
            return back(304)->with('error', 'Bir şeyler ters gitti!');
        return back()->with('success', 'Alanın aktif özelliği düzenlendi!');
    }
}
