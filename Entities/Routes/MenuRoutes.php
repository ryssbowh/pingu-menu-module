<?php

namespace Pingu\Menu\Entities\Routes;

use Pingu\Entity\Support\Routes\BaseEntityRoutes;

class MenuRoutes extends BaseEntityRoutes
{
    protected $inheritsEntityRoutes = false;
    
    /**
     * @inheritDoc
     */
    protected function routes(): array
    {
        return [
            'admin' => [
                'index', 'create', 'store', 'edit', 'update', 'patch', 'confirmDelete', 'delete', 'indexRevisions', 'editRevision', 'editItems', 'patchItems'
            ],
            'ajax' => [
                'index', 'view', 'create', 'store', 'edit', 'update', 'patch', 'delete', 'patchItems'
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
}