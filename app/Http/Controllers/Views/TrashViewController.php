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
                'primary_text' => 'kategoriler',
                'route_prefix' => 'categories',
                'image' => 'https://i.redd.it/nf59c1x7m2l91.jpg'
            ],
            'post' => [
                'primary_text' => 'gönderiler',
                'route_prefix' => 'posts',
                'image' => 'https://i.redd.it/nf59c1x7m2l91.jpg'
            ],
            'field' => [
                'primary_text' => 'alanlar',
                'route_prefix' => 'fields',
                'image' => 'https://i.redd.it/nf59c1x7m2l91.jpg'
            ]
        ];
        return view('admin.pages.trash.index', compact('resources'));
    }
}
