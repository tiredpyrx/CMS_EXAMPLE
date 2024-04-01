<?php

namespace App\Http\Controllers\Views;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use Illuminate\Http\Request;

class TrashViewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        // gate is user authorized to view trash?
        $resources = [
            'category' => [
                'all_count' => Category::count(),
                'title' => 'Kategoriler',
                'route_prefix' => 'categories',
            ],
            'post' => [
                'all_count' => Post::count(),
                'title' => 'Gönderiler',
                'route_prefix' => 'posts',
            ],
            'field' => [
                'all_count' => Field::count(),
                'title' => 'Alanlar',
                'route_prefix' => 'fields',
            ]
        ];
        return view('admin.pages.trash.index', compact('resources'));
    }
}
