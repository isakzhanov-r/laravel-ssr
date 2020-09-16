<?php

use IsakzhanovR\Ssr\Services\Renderer;

if (!function_exists('ssr')) {
    function ssr(string $entry = null)
    {
        if (is_null($entry)) {
            return app()->make(Renderer::class);
        }

        return app()->make(Renderer::class)->entry($entry);
    }
}
