<?php

namespace Pingu\Menu\Entities\Uris;

use Pingu\Entity\Support\BaseEntityUris;
use Pingu\Menu\Entities\Menu;

class MenuItemUris extends BaseEntityUris
{
    protected function uris(): array
    {
        return [
            'create' => Menu::routeSlug().'/{'.Menu::routeSlug().'}/items/create',
        ];
    }
}