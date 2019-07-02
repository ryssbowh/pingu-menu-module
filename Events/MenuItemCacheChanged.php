<?php

namespace Pingu\Menu\Events;

use Illuminate\Queue\SerializesModels;
use Pingu\Menu\Entities\MenuItem;

class MenuItemCacheChanged
{
    use SerializesModels;

    public $item;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MenuItem $item)
    {
        $this->item = $item;
    }
}
