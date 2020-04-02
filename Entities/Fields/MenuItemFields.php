<?php

namespace Pingu\Menu\Entities\Fields;

use Pingu\Field\BaseFields\Boolean;
use Pingu\Field\BaseFields\Integer;
use Pingu\Field\BaseFields\Model;
use Pingu\Field\BaseFields\Text;
use Pingu\Field\Support\FieldRepository\BaseFieldRepository;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;

class MenuItemFields extends BaseFieldRepository
{
    protected function fields(): array
    {
        return [
            New Text('name'),
            New Text('class'),
            new Integer('weight'),
            new Boolean('active'),
            New Text('url'),
            new Model(
                'menu',
                [
                    'model' => Menu::class,
                    'textField' => 'name', 
                    'includeNoValue' => false,
                    'required' => true
                ]
            ),
            new Model(
                'parent',
                [
                    'model' => MenuItem::class,
                    'textField' => 'name'
                ]
            ),
            new Model(
                'permission',
                [
                    'model' => Permission::class,
                    'items' => Permission::orderBy('name')->get(),
                    'textField' => 'name',
                    'noValueLabel' => 'No permission',
                    'label' => 'Viewing permission'
                ]
            )
        ];
    }
}