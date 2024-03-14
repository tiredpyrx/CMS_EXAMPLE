<?php

namespace App\Http\Controllers\Resources;

use App\Actions\FilterRequest;
use App\Actions\ToggleActive;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldActiveRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\Field;
use App\Services\FieldService;

class FieldController extends Controller
{

    private $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $modelName, int $modelId)
    {
        $instance = getModel($modelName);
        $model = $instance->find($modelId);
        return view('admin.pages.resources.field.create.index', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFieldRequest $request, string $modelName, int $modelId, FilterRequest $filterRequest)
    {
        $instance = getModel($modelName);
        $model = $instance->find($modelId);
        $filtered = $filterRequest->execute($request, 'field');
        $this->fieldService->create($filtered, $model);
        $modelName = match ($modelName) {
            'category' => 'categories',
            'blueprint' => 'blueprints'
        };
        return to_route($modelName . '.edit', $model->id)->with('success', 'Alan başarıyla eklendi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        $category = $field->category;
        return view('admin.pages.resources.field.edit.index', compact('field', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFieldRequest $request, Field $field)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        //
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
