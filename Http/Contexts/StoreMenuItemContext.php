<?php

namespace Pingu\Menu\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\StoreContext;

class StoreMenuItemContext extends StoreContext
{
    /**
     * @inheritDoc
     */
    protected function redirect()
    {
        return redirect(Menu::uris()->make('editItems', $this->object->menu, $this->getRouteScope()));
    }
}