<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontController extends Controller
{
    public function __invoke(string $slug)
    {
        // foreach (Category::where('have_details', 1)->get()->all() as $category) {
        //     foreach (Post::where([
        //         ['category_id', $category->id],
        //         ['active', 1]
        //     ])->get()->all() as $post) {
        //         if ($post->slug == $slug) {
        //             $posts = $category->posts;
        //             $postsCount = $category->posts_count;
        //             $datas = ['post' => $post, 'posts' => $posts, 'postCount' => $postsCount, 'postTitle' => $post->getTitle()];
        //             $datas['viewName'] = match (!is_null($category->view)) {
        //                 true => $category?->view,
        //                 default => $post?->view,
        //             };
        //             if ($category->view) {
        //                 $datas['viewName'] = $category->view;
        //                 return view('front.pages.' . $category->view, $datas);
        //             } else if ($post->slug) {
        //                 return view('front.pages.' . $post->field('view'), $datas);
        //             }
        //         }
        //     };
        // };

        // throw new NotFoundHttpException;
    }
}
