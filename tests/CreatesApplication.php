<?php

namespace Tests;

use App\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

trait CreatesApplication
{
    protected $user;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('db:seed');

        $this->user = factory(User::class)->create();
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
