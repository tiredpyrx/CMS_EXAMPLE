<?php

namespace App\Http\Controllers\Resources;

use App\Actions\FilterRequest;
use App\Actions\GetUpdatedDatas;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{

    private $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = Category::paginate(10, ['*'], 'pag');
        return view('admin.pages.resources.category.index.index', compact('categories'));
    }


    public function create()
    {
        return view('admin.pages.resources.category.create.index');
    }


    public function store(StoreCategoryRequest $request, FilterRequest $filterRequest)
    {
        $filtered = $filterRequest->execute($request, 'category');
        $category = $this->categoryService->create($filtered);
        return to_route('categories.show', $category->id)->with('success', 'Kategori başarıyla eklendi!');
    }


    public function show(Category $category)
    {
        $posts = $category->posts()->paginate(10);
        return view('admin.pages.resources.category.show.index', compact('category', 'posts'));
    }


    public function edit(Category $category)
    {
        $fields = $category->fields()->paginate(10);
        return view('admin.pages.resources.category.edit.index', compact('category', 'fields'));
    }


    public function update(UpdateCategoryRequest $request, Category $category, FilterRequest $filterRequest, GetUpdatedDatas $getUpdatedDatas)
    {
        $safeRequest = $filterRequest->execute($request, 'category');
        $updated = $getUpdatedDatas->execute($safeRequest, 'category');
        $success = $this->categoryService->update($category, $updated);
        if (!$success)
            return back(304)->with('error', 'Bir şeyler ters gitti!');
        return back()->with('success', 'Kategori başarıyla güncellendi!');
    }


    public function destroy(Category $category)
    {
        //
    }
}
