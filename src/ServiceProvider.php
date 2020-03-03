<?php


namespace IsakzhanovR\Ssr;


use IsakzhanovR\Ssr\Engines\Node;
use IsakzhanovR\Ssr\Services\Renderer;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ssr.php' => \config_path('ssr.php'),
        ], 'config');

        $this->app->alias(Renderer::class, 'ssr');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ssr.php', 'ssr');

        $this->app->singleton(Node::class, function () {
            return new Node($this->app->config->get('ssr.temp_storage'));
        });
    }

    public function provides()
    {
        return ['ssr'];
    }
}
