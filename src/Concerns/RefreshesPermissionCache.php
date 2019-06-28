<?php

namespace Caffeinated\Shinobi\Concerns;

trait RefreshesPermissionCache
{
    public static function bootRefreshesPermissionCache()
    {
        static::saved(function() {
            cache()->tags(config('shinobi.cache.tag'))->flush();
        });

        static::deleted(function() {
            cache()->tags(config('shinobi.cache.tag'))->flush();
        });
    }
}