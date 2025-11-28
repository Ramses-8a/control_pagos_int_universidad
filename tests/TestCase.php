<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\Concerns\CreatesApplication;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithAuthentication;

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
