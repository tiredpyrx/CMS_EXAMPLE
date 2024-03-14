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
        foreach (Category::where('have_details', 1)->get()->all() as $c) {
            foreach (Post::where('category_id', $c->id)->get()->all() as $post) {
                if ($post->slug == $slug) {
                    $page = $post;
                    if ($c->view) {
                        return view('front.pages.' . $c->view, ['page' => $page]);
                    } else if ($post->slug)
                        return view('front.pages.' . $post->view, ['page' => $page]);
                }
            };
        };

        throw new NotFoundHttpException;
    }
}
