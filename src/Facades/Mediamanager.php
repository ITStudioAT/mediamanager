<?php

namespace Itstudioat\Mediamanager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Itstudioat\Mediamanager\Mediamanager
 */
class Mediamanager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Itstudioat\Mediamanager\Mediamanager::class;
    }
}
