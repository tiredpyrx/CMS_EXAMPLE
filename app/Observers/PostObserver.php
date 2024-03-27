<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class PostObserver
{

    public function created(Post $post): void
    {
        $publishDate = request()->input('publish_date');
        $now = now();
        if ($publishDate) {
            $publishDate = Carbon::parse($publishDate);
            $post->update([
                'publish_date' => $publishDate,
                'published' => ($publishDate <= $now)
            ]);
        } else {
            $post->update([
                'publish_date' => $now,
                'published' => true
            ]);
        }
    }

    public function updated(Post $post): void
    {
        $publishDate = request()->input('publish_date');
        $now = now();
        if ($publishDate) {
            $publishDate = Carbon::parse($publishDate);
            $post->updateQuietly([
                'publish_date' => $publishDate,
                'published' => ($publishDate <= $now)
            ]);
        } else {
            $post->updateQuietly([
                'publish_date' => $now,
                'published' => true
            ]);
        }

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
