<?php

namespace Pingu\Menu\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\DeleteContext;
use Pingu\Menu\Entities\Menu;

class DeleteMenuItemContext extends DeleteContext
{
    /**
     * @inheritDoc
     */
    protected function redirect()
    {
        return redirect(Menu::uris()->make('editItems', $this->object->menu, $this->getRouteScope()));
    }
}