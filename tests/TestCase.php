<?php

namespace Tests;


use IsakzhanovR\Ssr\ServiceProvider;
use Symfony\Component\Process\Process;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->createVueApplication();
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

    private function createVueApplication()
    {
        $process = new Process(['cd ' . __DIR__ . ' && yarn install && yarn dev']);
        $process->setTimeout(5000);
        try {
            $process->mustRun();
            echo $process->getOutput();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
