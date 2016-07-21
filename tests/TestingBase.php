<?php

namespace EpicLog\Tests;

use Illuminate\Foundation\Testing\TestCase;

class TestingBase extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }
    
    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register('EpicLog\EpicLogServiceProvider');

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }
}
