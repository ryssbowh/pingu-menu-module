<?php

namespace Pingu\Menu\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\CreateContext;

class CreateMenuItemContext extends CreateContext
{
    /**
     * @inheritDoc
     */
    public function getFields(): Collection
    {
        $fields = parent::getFields();
        $fields->get('menu')->option('default', request()->route()->parameters['menu']);
        return $fields;
    }
}