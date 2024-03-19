<?php

namespace App\Http\Controllers\Resources;

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
        $fields = Field::where('category_id', $category->id)->get();
        $changeFrequencyOptions = Post::getChangeFrequencyValues();
        return view('admin.pages.resources.post.create.index', compact('category', 'fields', 'changeFrequencyOptions'));
    }

    public function store(StorePostRequest $request, Category $category)
    {
        $safeRequest = $request->only('title');
        $post = $this->postService->create($request, $safeRequest, $category);
        $this->postService->registerFields($post, $request);
        return to_route('categories.show', $category->id)->with('success', 'Gönderi başarıyla eklendi!');
    }

    public function show(Post $post)
    {
        //
    }

    public function edit(Post $post)
    {
        $fields = Field::where('post_id', $post->id)->get();
        $category = Category::find($post->category_id);
        $changeFrequencyOptions = Post::getChangeFrequencyValues();
        return view('admin.pages.resources.post.edit.index', compact('post', 'category', 'fields', 'changeFrequencyOptions'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $result = [];
        $result[] = $this->postService->update($request, $post);
        $result[] = $this->postService->updateFields($request, $post);
        // $title = $request->input('title');
        // if ($post->title != $title) $post->update(['title' => $title]);
        // $active = $request->has('active');
        // if ($post->active != $active) $post->update(['active' => $active]);
        // $publishDate = $request->input('publish_date');
        // if ($post->publish_date != $publishDate)
        //     $post->update(['publish_date' => $publishDate]);
        if (in_array(false, $result))
            return back()->with('error', 'Gönderiyi güncellerken bir şeyler ters gitti!');
        return back()->with('success', 'Gönderi başarıyla güncellendi!');
    }

    public function destroy(Post $post)
    {
        $post->delete();
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
