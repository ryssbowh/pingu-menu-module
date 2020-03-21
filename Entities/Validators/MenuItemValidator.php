<?php

namespace Pingu\Menu\Entities\Validators;

use Pingu\Field\Support\FieldValidator\BaseFieldsValidator;

class MenuItemValidator extends BaseFieldsValidator
{
    protected function rules(bool $updating): array
    {
        return [
            'name' => 'required',
            'menu' => 'required',
            'active' => 'boolean',
            'url' => 'sometimes|valid_url',
            'menu' => 'required|exists:menus,id',
            'permission' => 'nullable|exists:permissions,id',
            'weight' => 'nullable',
            'class' => 'string',
            'parent' => 'exists:menu_items,id'
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'menu.required' => 'Menu is required',
            'url.valid_url' => 'This url doesn\'t exist'
        ];
    }
}