<?php

namespace MehediIitdu\CoreComponentRepository;

use Illuminate\Support\ServiceProvider;

class CoreComponentRepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('core-component-repository.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'core-component-repository');

        $this->app->singleton('core-component-repository', function () {
            return new CoreComponentRepository;
        });
    }
}
