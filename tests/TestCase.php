<?php

namespace Tests;


use IsakzhanovR\Ssr\ServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        $app->bind('path.public', function () {
            return __DIR__ . '/public';
        });

        return [ServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('app.debug', true);
    }
}
