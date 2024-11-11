<?php

namespace App\Providers;

use App\helpers\ExternalApiHelper;
use App\Models\Project;
use App\Observers\ProjectObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ExternalApiHelper::class, function() {
            return new ExternalApiHelper('hello from app service providerrr');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Project::observe(ProjectObserver::class);
    }
}
