<?php

namespace IsakzhanovR\Ssr;

use Illuminate\Support\Facades\Config;
use IsakzhanovR\Ssr\Engines\Node;
use IsakzhanovR\Ssr\Services\Renderer;
use function config_path;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ssr.php' => config_path('ssr.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ssr.php', 'ssr');

        $this->app->singleton(Node::class, function () {
            return new Node(Config::get('ssr.temp_storage'));
        });

        $this->app->alias(Renderer::class, 'ssr');
    }

    public function provides()
    {
        return ['ssr'];
    }
}
