<?php

namespace IsakzhanovR\Ssr\Facades;

use Illuminate\Support\Facades\Facade;

class Ssr extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ssr';
    }
}
