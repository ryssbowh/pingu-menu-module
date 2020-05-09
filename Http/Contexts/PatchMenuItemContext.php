<?php

namespace Pingu\Menu\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\PatchContext;

class PatchMenuItemContext extends PatchContext
{
    /**
     * @inheritDoc
     */
    protected function redirect(Collection $patched)
    {
        return redirect(Menu::uris()->make('editItems', $patched->first()->menu, $this->getRouteScope()));
    }
}