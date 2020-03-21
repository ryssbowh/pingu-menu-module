<?php

namespace Pingu\Menu\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class MenuValidator extends BaseFieldsValidator
{
    protected function rules(bool $updating): array
    {
        return [
            'name' => 'required',
            'machineName' => 'required|unique:menus,machineName'
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'machineName.required' => 'Machine Name is required',
            'machineName.unique' => 'Machine name already exists'
        ];
    }
}