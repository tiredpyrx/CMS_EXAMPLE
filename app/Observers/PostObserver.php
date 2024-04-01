<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class PostObserver
{

    private $service;

    public function __construct()
    {
        $this->service = (new PostService());
    }

    public function created(Post $post): void
    {
        $this->service->handlePublish($post);
    }

    public function updated(Post $post): void
    {
        $this->service->handlePublish($post);

        if ($post->slug)
            $this::tryToLogToSitemap();
    }

    public function deleted(Post $post): void
    {
        $this::tryToLogToSitemap();
    }

    public function restored(Post $post): void
    {
        $this::tryToLogToSitemap();
    }

    public function forceDeleted(Post $post): void
    {
        $this::tryToLogToSitemap();
    }

    private static function tryToLogToSitemap()
    {
        Artisan::call('app:log-to-sitemap');
    }
}
