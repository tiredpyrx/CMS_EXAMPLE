<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
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
        // Artisan::call('app:log-to-sitemap');
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // Artisan::call('app:log-to-sitemap');
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
