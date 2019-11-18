<?php

namespace Pingu\Menu\Entities\Routes;

use Pingu\Entity\Support\BaseEntityRoutes;

class MenuRoutes extends BaseEntityRoutes
{
    protected function routes(): array
    {
        return [
            'admin' => [
                'editItems', 'patchItems'
            ],
            'ajax' => [
                'patchItems'
            ]
        ];
    }

    protected function middlewares(): array
    {
        return [
            'editItems' => 'can:edit,menu',
            'patchItems' => 'can:edit,menu',
        ];
    }

    protected function methods(): array
    {
        return [
            'editItems' => 'get',
            'patchItems' => 'patch'
        ];
    }

    protected function names(): array
    {
        return [
            'admin.index' => 'menu.admin.menus',
            'admin.create' => 'menu.admin.menus.create'
        ];
    }
}