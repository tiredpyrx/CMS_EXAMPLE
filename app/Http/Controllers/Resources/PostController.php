<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

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
        $fields = $category->fields;
        return view('admin.pages.resources.post.create.index', compact('category', 'fields'));
    }

    public function store(StorePostRequest $request, Category $category, PostService $postService)
    {
        $safeRequest = $request->only('title', 'publish_date');
        $additional = ['user_id' => auth()->id(), 'category_id' => $category->id];
        if ($request->publish_date >= now())
            $additional['published'] = true;
        $merged = array_merge($safeRequest, $additional);
        $post = Post::create($merged);
        $postService->registerFields($post, $request);
        return to_route('categories.show', $category->id)->with('success', 'Gönderi başarıyla eklendi!');
    }

    public function show(Post $post)
    {
        //
    }

    public function edit(Post $post)
    {
        //
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        //
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
