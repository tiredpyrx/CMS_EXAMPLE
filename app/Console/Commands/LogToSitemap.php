<?php

namespace App\Console\Commands;


use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File as FacadesFile;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class LogToSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:log-to-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $postsThatWillLogToSitemap = [
            ...(Post::where([
                ['published', 1],
                ['active', 1],
            ])->get())
        ];

        $this::wipeSitemapContent();

        $sitemap = Sitemap::create();
        $sitemap->add(Url::create('/'));
        foreach ($postsThatWillLogToSitemap as $post) {
            $sitemap
                ->add(Url::create($post->slug)
                    ->setLastModificationDate(Carbon::parse($post->updated_at))
                    ->setChangeFrequency($post->field('changefreq'))
                    ->setPriority($post->field('priority')));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sayfalar başarıyla sitemap.xml dosyasına eklendi.');
    }

    private static function wipeSitemapContent()
    {
        if (FacadesFile::get(public_path('sitemap.xml')))
            FacadesFile::put(public_path('sitemap.xml'), '');
    }
}
