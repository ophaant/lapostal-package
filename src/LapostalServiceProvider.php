<?php

namespace Ophaant\Lapostal;

use Illuminate\Support\ServiceProvider;
use Ophaant\Lapostal\Console\Commands\LapostalInstallPackage;

class LapostalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LapostalInstallPackage::class,
            ]);
            $this->publishResources();
        }
    }

    public function register()
    {


    }
    protected function publishResources()
    {
        $this->publishesMigrations([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'lapostal-migrations');

        $this->publishes([
            __DIR__ . '/../database/seeders/' => database_path('seeders'),
        ], 'lapostal-seeders');

        $this->publishes([
            __DIR__ . '/../storage/postal_code/' => storage_path('app/postal_code'),
        ], 'lapostal-postal_code');
    }
}

