<?php

namespace Pingu\Menu\Entities\Uris;

use Pingu\Core\Support\Uris\BaseModelUris;

class MenuUris extends BaseModelUris
{
    protected function uris(): array
    {
        return [
            'editItems' => '@slug@/{@slug@}/items',
            'patchItems' => '@slug@/{@slug@}/items'
        ];
    }
}