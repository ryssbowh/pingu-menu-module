<?php

namespace Pingu\Menu\Http\Contexts;

use Pingu\Core\Http\Contexts\EditContext;
use Pingu\Field\Contracts\HasFieldsContract;

class EditMenuItemContext extends EditContext
{
    /**
     * @inheritDoc
     */
    public function getValidationRules(HasFieldsContract $model): array
    {
        $rules = parent::getValidationRules($model);
        unset($rules['machineName']);
        return $rules;
    }
}