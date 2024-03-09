<?php

namespace App\Providers;

use App\Models\Blueprint;
use App\Models\Category;
use App\Models\Field;
use App\Models\Post;
use App\Models\User;
use App\Observers\BlueprintObserver;
use App\Observers\CategoryObserver;
use App\Observers\FieldObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\post;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Category::observe(CategoryObserver::class);
        Blueprint::observe(BlueprintObserver::class);
        Post::observe(PostObserver::class);
        Field::observe(FieldObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
