<?php

namespace Pingu\Menu\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Pingu\Menu\Events\MenuCacheChanged;
use Pingu\Menu\Events\MenuItemCacheChanged;
use Pingu\Menu\Listeners\EmptyMenuCache;
use Pingu\Menu\Listeners\EmptyMenuItemCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MenuCacheChanged::class => [
            EmptyMenuCache::class
        ],
        MenuItemCacheChanged::class => [
            EmptyMenuItemCache::class
        ]
    ];
}