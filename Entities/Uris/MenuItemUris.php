<?php

namespace Pingu\Menu\Entities\Uris;

use Pingu\Core\Support\Uris\BaseModelUris;
use Pingu\Menu\Entities\Menu;

class MenuItemUris extends BaseModelUris
{
    protected function uris(): array
    {
        return [
            'create' => Menu::routeSlug().'/{'.Menu::routeSlug().'}/items/create',
        ];
    }
}