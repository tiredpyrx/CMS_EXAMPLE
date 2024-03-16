<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;

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
            $post->publish_date = $publishDate;
            $post->published = ($publishDate <= $now);
        } else {
            $post->publish_date = $now;
            $post->published = true;
        }
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
        //
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
