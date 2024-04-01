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

    public function create(Category $category)
    {
        $defaultColumnValue = Field::getDefaultColumnValue();
        $defaultTypeValue = Field::getDefaultTypeValue();
        $typesWithLabels = Field::getTypesWithLabels();
        return view('admin.pages.resources.field.create.index', compact('category', 'typesWithLabels', 'defaultTypeValue', 'defaultColumnValue'));
    }

    public function store(StoreFieldRequest $request, Category $category, FilterRequest $filterRequest)
    {
        $filtered = $filterRequest->execute($request, 'field');
        $this->fieldService->create($filtered, $category);
        return to_route('categories.edit', $category->id)->with('success', 'Alan başarıyla eklendi!');
    }

    public function show(Field $field)
    {
        $infos = $field->getMassAssignableAttributes();
        $infos = array_filter($infos, fn ($d) => $d);
        $infos = array_combine(
            array_map('ucfirst', array_keys($infos)),
            array_values($infos)
        );
        return view('admin.pages.resources.field.show.index', compact('field', 'infos'));
    }

    public function edit(Field $field)
    {
        $category = $field->category;
        $typesWithLabels = Field::getTypesWithLabels();
        $fieldFilesCount = $field->files->count();
        $fieldHasAnyFileWithSource = $field->files->pluck('source')->filter(fn($source) => !is_null($source))->count();
        return view('admin.pages.resources.field.edit.index', compact('field', 'category', 'typesWithLabels', 'fieldFilesCount', 'fieldHasAnyFileWithSource'));
    }

    public function update(UpdateFieldRequest $request, Field $field, FilterRequest $filterRequest, GetUpdatedDatas $getUpdatedDatas)
    {
        $safeRequest = $filterRequest->execute($request, 'field');
        $success = $this->fieldService->updateFields($field, $safeRequest);
        $flashMessage = match ($success) {
            true => ['success', 'Alan başarıyla güncellendi!'],
            false => ['error', 'Bir şeyler ters gitti!']
        };
        return back()->with($flashMessage[0], $flashMessage[1]);
    }

    public function destroy(Field $field)
    {
        return $this->fieldService->appendToTrash($field);
    }

    public function updateActive(UpdateFieldActiveRequest $request, ToggleActive $toggleActive, string $modelName)
    {
        $success = $toggleActive->execute($request, $modelName);

        $field = getModelClass($modelName)::where($request->get('primaryKey'), $request->get('primaryValue'))->first();

        if ($request->expectsJson() || $request->ajax())
            return 1;

        if (!$success)
            return back(304)->with('error', 'Bir şeyler ters gitti!');
        return back()->with('success', 'Alanın aktif özelliği düzenlendi!');
    }
}
