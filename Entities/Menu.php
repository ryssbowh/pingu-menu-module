<?php

namespace Pingu\Menu\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
use Pingu\Core\Contracts\AdminableModel as AdminableModelContract;
use Pingu\Core\Contracts\HasContextualLinks;
use Pingu\Core\Contracts\HasItems;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\APIableModel;
use Pingu\Core\Traits\AdminableModel;
use Pingu\Forms\Fields\ManyModel;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Renderers\ModelTree;
use Pingu\Forms\Traits\FormableModel;
use Pingu\Jsgrid\Contracts\JsGridableModel as JsGridableModelContract;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\JsGridableModel;

class Menu extends BaseModel implements JsGridableModelContract, HasItems, HasContextualLinks, AdminableModelContract
{
	use FormableModel, JsGridableModel, APIableModel, AdminableModel;

    protected $fillable = ['name', 'machineName'];

    public static $fieldDefinitions = [
		'name' => [
			'type' => Text::class
		],
		'machineName' => [
			'type' => Text::class,
			'label' => 'Machine Name'
		],
        'items' => [
            'type' => ManyModel::class,
            'model' => MenuItem::class,
            'textField' => 'name',
            'renderer' => ModelTree::class
        ]
	];

    public static $validationRules = [
        'name' => 'required',
        'machineName' => 'required|unique:menus,machineName,{machineName}'
    ];

    public static $validationMessages = [
        'name.required' => 'Name is required',
        'machineName.required' => 'Machine Name is required',
        'machineName.unique' => 'Machine name already exists'
    ];

    public static $addFields = ['name', 'machineName'];

    public static $editFields = ['name'];

    /**
     * A menu can have several items
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): Relation
    {
        return $this->hasMany(MenuItem::class)->orderBy('weight');
    }

    public static function jsGridFields()
    {
    	return [
    		'name' => [
    			'type' => JsGridText::class
    		]
    	];
    }

    public function getContextualLinks(): array
    {
        return [
            'edit' => [
                'title' => 'Edit',
                'url' => $this::transformAdminUri('edit', [$this->id], true)
            ],
            'items' => [
                'title' => 'Items',
                'url' => '/admin/'.$this::routeSlug().'/'.$this->id.'/items'
            ]
        ];
    }

    public function getRootItems()
    {
        return $this->items->filter(function($item, $key){
            return is_null($item->parent);
        });
    }

    public function getActiveRootItems()
    {
        return $this->items->filter(function($item, $key){
            return (is_null($item->parent) and $item->active);
        });
    }

    public static function findByName(string $machineName)
    {
        return static::where(['machineName' => $machineName])->first();
    }

    public function getRootNextWeight()
    {
        return $this->items()->where(['parent_id' => null])->orderBy('weight', 'desc')->first()->weight + 1;
    }

    public static function adminEditItemsUri()
    {
        return static::routeSlug().'/{'.static::routeSlug().'}/items';
    }
}
