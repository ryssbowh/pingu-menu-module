<?php

namespace Pingu\Menu\Http\Contexts;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Contexts\UpdateContext;
use Pingu\Field\Contracts\HasFieldsContract;

class UpdateMenuContext extends UpdateContext
{
    /**
     * @inheritDoc
     */
    public function getValidationRules(HasFieldsContract $model): array
    {
        return $model->fieldRepository()->validationRules()->except('machineName')->toArray();
    }
}