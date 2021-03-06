<?php

namespace Pingu\Menu\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Menu\Entities\Menu;

class MenuCacheChanged
{
    use SerializesModels;

    public $menu;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }
}
