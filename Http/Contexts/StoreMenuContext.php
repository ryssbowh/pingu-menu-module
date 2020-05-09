<?php

namespace Pingu\Menu\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\StoreContext;

class StoreMenuContext extends StoreContext
{
    /**
     * @inheritDoc
     */
    public function redirect()
    {
        return redirect(Menu::uris()->make('editItems', $this->object, $this->getRouteScope()));
    }
}