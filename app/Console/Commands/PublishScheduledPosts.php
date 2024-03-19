<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish post if post\'s publish date is greater or equals to current date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $postsThatWillBePublished = Post::where('publish_date', '<=', now())->where('active', 1)->get();
        $postsThatWillBePublished->each(fn($post) => (new PostService())->publish($post));

        $this->info('Planlanmış gönderiler başarıyla yayınlandı!');
    }
}
