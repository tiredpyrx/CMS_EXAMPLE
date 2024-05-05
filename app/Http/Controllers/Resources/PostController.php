<?php

namespace App\Http\Controllers\Resources;

use App\Enums\FieldTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use App\Services\PostService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{

    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        //
    }

    public function create(Category $category)
    {
        $fields = Field::where('category_id', $category->id)->where('active', 1)->get();
        $changeFrequencyOptions = Post::getChangeFrequencyValues();
        return view('admin.pages.resources.post.create.index', compact('category', 'fields', 'changeFrequencyOptions'));
    }

    public function store(StorePostRequest $request, Category $category)
    {
        $post = $this->postService->create($request, $category);
        $this->postService->registerFields($post, $request);
        if ($category->isSpecial())
            $this->postService->handleSpecialCategoryPostFeaturesOnCreate($post);
        return to_route('categories.show', $category->id)->with('success', 'Gönderi başarıyla eklendi!');
    }

    public function show(Post $post)
    {
        // $category = $post->category;
        // return view('admin.pages.resources.category.show.index', compact('post', 'category'));
    }

    public function edit(Post $post)
    {
        $fields = Field::where('post_id', $post->id)->where('active', 1)->get();
        $category = Category::find($post->category_id);
        $changeFrequencyOptions = Post::getChangeFrequencyValues();
        $mediaFields = $post->getFieldsWhenTypes(FieldTypes::getMediaTypes());
        $isMediaFilesHasAnySource = $mediaFields->map(fn ($field) => $field->files)->flatten()->pluck('source')->count() > 0;
        return view('admin.pages.resources.post.edit.index', compact('post', 'category', 'fields', 'changeFrequencyOptions', 'mediaFields', 'isMediaFilesHasAnySource'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $result = [];
        $result[] = $this->postService->update($request, $post);
        $result[] = $this->postService->updateFields($request, $post);

        if (in_array(false, $result))
            return back()->with('error', 'Gönderiyi güncellerken bir şeyler ters gitti, lütfen güncellenmemiş olabilen alanlara bakınız!');
        return back()->with('success', 'Gönderi başarıyla güncellendi!');
    }

    public function destroy(Post $post)
    {
        if (Category::find($post->category_id)->isSpecial())
            $this->postService->handleSpecialCategoryPostFeaturesOnDelete($post);
        return $post->delete();
    }

    public function deleteAllSelected(Request $request)
    {
        $ids = $request->input('ids');
        $success = $this->postService->deleteAllSelected($ids);

        if ($request->expectsJson() || $request->ajax())
            return $success;

        if (!$success)
            return back(304)->with('error', 'Bir şeyler ters gitti!');
        return back()->with('success', 'Post aktif özelliği düzenlendi!');
    }
}
