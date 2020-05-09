<?php

namespace Pingu\Menu\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\EditContext;

class EditMenuContext extends EditContext
{
    /**
     * @inheritDoc
     */
    public function getFields(): Collection
    {
        $fields = parent::getFields();
        $fields->get('machineName')->option('disabled', true);
        return $fields;
    }
}