<?php

namespace Pingu\Menu\Entities\Uris;

use Pingu\Entity\Support\Uris\BaseEntityUris;

class MenuUris extends BaseEntityUris
{
    protected function uris(): array
    {
        return [
            'editItems' => $this->entity::routeSlug().'/{'.$this->entity::routeSlug().'}/items',
            'patchItems' => $this->entity::routeSlug().'/{'.$this->entity::routeSlug().'}/items'
        ];
    }
}