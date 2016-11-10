<?php

namespace Caffeinated\Shinobi\Facades;

use Illuminate\Support\Facades\Facade;

class Shinobi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shinobi';
    }
}
