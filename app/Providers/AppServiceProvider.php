<?php

namespace App\Providers;

use App\Repositories\Base\BaseRepositoryAbstract;
use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepositoryAbstract::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        # Set the default string length
        Schema::defaultStringLength(191);
    }
}
