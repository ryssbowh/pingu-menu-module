<?php

namespace Pingu\Menu\Entities\Fields;

use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;

class MenuFields extends BaseFieldRepository
{
    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            new Text(
                'name',
                [
                    'required' => true
                ]
            ),
            new Text(
                'machineName',
                [
                    'label' => 'Machine Name',
                    'required' => true,
                    'dashifyFrom' => 'name'
                ]
            )
        ];
    }

    /**
     * @inheritDoc
     */
    protected function rules(): array
    {
        return [
            'name' => 'required',
            'machineName' => 'required|unique:menus,machineName'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'machineName.required' => 'Machine Name is required',
            'machineName.unique' => 'Machine name already exists'
        ];
    }
}