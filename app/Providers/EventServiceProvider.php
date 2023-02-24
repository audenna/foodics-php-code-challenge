<?php

namespace App\Providers;


use App\Models\Ingredient;
use App\Models\ProductIngredient;
use App\Observers\IngredientObserver;
use App\Observers\ProductIngredientObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


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
        $this->bootModelObservers();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    /**
     * @return void
     */
    private function bootModelObservers(): void
    {
        ProductIngredient::observe(ProductIngredientObserver::class);
        Ingredient::observe(IngredientObserver::class);
    }
}
