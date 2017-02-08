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
            __DIR__.'/config/epiclog.php' => app()->make('path.config') . DIRECTORY_SEPARATOR . 'epiclog.php'
        ], 'epiclog');

        // bind the Custom Logger as a singleton, so our custom channels are preserved
        app()->singleton('epiclog', function ($app) {
            return $app->make('EpicLog\CustomLogger');
        });

        // boot up epiclog and initialize
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
