<?php

namespace Pingu\Menu\Entities\Actions;

use Pingu\Entity\Support\BaseEntityActions;
use Pingu\Taxonomy\Entities\TaxonomyItem;

class MenuActions extends BaseEntityActions
{
    public function actions(): array
    {
        return [
            'edit' => [
                'label' => 'Edit',
                'url' => function ($entity) {
                    return $entity::uris()->make('edit', $entity, adminPrefix());
                },
                'access' => function ($entity) {
                    return \Auth::user()->hasPermissionTo('edit menus');
                }
            ],
            'editItems' => [
                'label' => 'List items',
                'url' => function ($entity) {
                    return $entity::uris()->make('editItems', $entity, adminPrefix());
                },
                'access' => function ($entity) {
                    return \Auth::user()->hasPermissionTo('view menus');
                }
            ],
            'delete' => [
                'label' => 'Delete',
                'url' => function ($entity) {
                    return $entity::uris()->make('confirmDelete', $entity, adminPrefix());
                },
                'access' => function ($entity) {
                    return \Auth::user()->hasPermissionTo('delete menus');
                }
            ],
        ];
    }
}