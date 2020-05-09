<?php

namespace Pingu\Menu\Entities\Actions;

use Pingu\Core\Support\Actions\BaseAction;
use Pingu\Entity\Support\Actions\BaseEntityActions;
use Pingu\Taxonomy\Entities\TaxonomyItem;

class MenuActions extends BaseEntityActions
{
    /**
     * @inheritDoc
     */
    public function actions(): array
    {
        return [
            'editItems' => new BaseAction(
                'List items',
                function ($entity) {
                    return $entity::uris()->make('editItems', $entity, adminPrefix());
                },
                function ($entity) {
                    return \Gate::check('view', $entity);
                },
                'admin'
            )
        ];
    }
}