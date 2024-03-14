<?php

namespace App\Http\Controllers\Resources;

use App\Actions\FilterRequest;
use App\Actions\GetUpdatedDatas;
use App\Actions\ToggleActive;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Field;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = Category::paginate(10, ['*'], 'pag');
        $paginationArray = $categories->links()->elements[0];
        return view('admin.pages.resources.category.index.index', compact('categories', 'paginationArray'));
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
        $posts = $category->posts()->orderByDesc('created_at')->paginate(10);
        return view('admin.pages.resources.category.show.index', compact('category', 'posts'));
    }


    public function edit(Category $category)
    {
        $fields = $category->fields()->paginate(10);
        $paginationArray  = $fields->links()->elements[0];
        $notEditableFields = Field::PRIMARY_HANDLERS;

        return view(
            'admin.pages.resources.category.edit.index',
            compact(
                'category',
                'fields',
                'paginationArray',
                'notEditableFields'
            )
        );
    }


    public function update(UpdateCategoryRequest $request, Category $category, FilterRequest $filterRequest, GetUpdatedDatas $getUpdatedDatas)
    {
        $safeRequest = $filterRequest->execute($request, 'category');
        $updated = $getUpdatedDatas->execute($safeRequest, 'category', $category->id);
        $success = $this->categoryService->update($category, $updated);
        if (!$success)
            return back(304)->with('error', 'Bir şeyler ters gitti!');
        return back()->with('success', 'Kategori başarıyla güncellendi!');
    }


    public function destroy(Request $request, Category $category)
    {
        // return $category;

        $success = $this->categoryService->destroy($category);
        if ($request->ajax() || $request->wantsJson())
            return $success;
        return to_route('categories.index')->with('success', 'Kategori başarıyla çöpe atıldı!');
    }

    public function restore(Category $category)
    {
        $category->restore();
        return to_route('categories.show', $category->id)->with('success', 'Kategori başarıyla canlandırıldı!');
    }

    public function forceDelete(Request $request, Category $category)
    {
        $category->forceDelete();
        if ($request->routeIs('trash.*'))
            return to_route('categories.index')->with('success', 'Kategori başarıyla silindi!');
        return back()->with('success', 'Kategori başarıyla silindi!');
    }

    public function updateActive(Request $request, ToggleActive $toggleActive, string $modelName)
    {
        $success = $toggleActive->execute($request, $modelName);

        if ($request->expectsJson() || $request->ajax())
            return 1;

        if (!$success)
            return back(304)->with('error', 'Bir şeyler ters gitti!');
        return back()->with('success', 'Kategori aktif özelliği düzenlendi!');
    }

    public function deleteAllSelected(Request $request)
    {
        $ids = $request->input('ids');
        $success = $this->categoryService->deleteAllSelected($ids);

        if ($request->expectsJson() || $request->ajax())
            return $success;

        if (!$success)
            return back(304)->with('error', 'Bir şeyler ters gitti!');
        return back()->with('success', 'Kategori aktif özelliği düzenlendi!');
    }

    public function deleteAllUnactives()
    {
        return Category::whereNot('active', true)->delete();
    }

    public function deleteAllUnactiveChildren(Request $request, Category $category)
    {
        $mname = $request->input('modelName');
        $model = getModel($mname);
        $model->where('category_id', $category->id)->whereNot('active', true)->get()->each(fn ($child) => $child->delete());
        return Category::whereNot('active', true)->delete();
    }

    public function updateIcon(Request $request, Category $category)
    {
        $icon = $request->json()->all()['data']['icon'];
        return $category->update(['icon' => $icon]);
    }
}
