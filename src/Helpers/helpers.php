<?php

use IsakzhanovR\Ssr\Services\Renderer;

if (!function_exists('ssr')) {
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function ssr(string $entry = null)
    {
        if (is_null($entry)) {
            return app()->make(Renderer::class);
        }

        return app()->make(Renderer::class)->entry($entry);
    }
}
