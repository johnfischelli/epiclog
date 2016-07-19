<?php

namespace EpicLog;

use Illuminate\Support\ServiceProvider;

class EpicLogServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/epiclog.php' => config_path('epiclog.php')
        ], 'epiclog');

        $epiclog = app()->make('EpicLog\EpicLog');
        $epiclog->init();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
