<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
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
    public function create(Category $category)
    {
        $fields = $category->fields;
        return view('admin.pages.resources.post.create.index', compact('category', 'fields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, Category $category, PostService $postService)
    {
        $safeRequest = $request->only('title');
        $additional = ['user_id' => auth()->id(), 'category_id' => $category->id];
        $merged = array_merge($safeRequest, $additional);
        $post = Post::create($merged);
        $postService->registerFields($post, $request);
        return to_route('categories.show', $category->id)->with('success', 'Gönderi başarıyla eklendi!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
