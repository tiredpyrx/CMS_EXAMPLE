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
        foreach (Category::where('have_details', 1)->get()->all() as $category) {
            foreach (Post::where('category_id', $category->id)->get()->all() as $post) {
                if ($post->slug == $slug) {
                    $page = $post;
                    $pages = $category->posts;
                    $pageCount = $category->posts_count;
                    $datas = ['page' => $page, 'pages' => $pages, 'pageCount' => $pageCount, 'pageTitle' => $page->getTitle()];
                    $datas['viewName'] = match (!is_null($category->view)) {
                        true => $category?->view,
                        default => $page?->view,
                    };
                    if ($category->view) {
                        $datas['viewName'] = $category->view;
                        return view('front.pages.' . $category->view, $datas);
                    } else if ($post->slug) {
                        return view('front.pages.' . $post->field('vissew'), $datas);
                    }
                }
            };
        };

        throw new NotFoundHttpException;
    }
}
