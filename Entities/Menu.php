<?php

namespace Pingu\Menu\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
use Pingu\Core\Contracts\Models\HasAdminRoutesContract;
use Pingu\Core\Contracts\Models\HasContextualLinksContract;
use Pingu\Core\Contracts\Models\HasItemsContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasAdminRoutes;
use Pingu\Core\Traits\Models\HasAjaxRoutes;
use Pingu\Core\Traits\Models\HasRouteSlug;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Text;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\Menu\Events\MenuCacheChanged;

class Menu extends BaseModel implements JsGridableContract, HasContextualLinksContract, HasAdminRoutesContract, HasItemsContract
{
    use JsGridable, Formable, HasAjaxRoutes, HasAdminRoutes, HasRouteSlug;

    protected $dispatchesEvents = [
        'saved' => MenuCacheChanged::class,
        'deleted' => MenuCacheChanged::class
    ];

    protected $fillable = ['name', 'machineName'];

    protected $casts = [
        'deletable' => 'boolean'
    ];

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['name', 'machineName'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['name'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'name' => [
                'field' => TextInput::class,
                'options' => [
                    'label' => 'Name',
                    'type' => Text::class
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            'machineName' => [
                'field' => TextInput::class,
                'options' => [
                    'label' => 'Machine Name',
                    'type' => Text::class
                ],
                'attributes' => [
                    'class' => 'js-dashify',
                    'data-dashifyfrom' => 'name',
                    'required' => true
                ]
            ],
            // 'items' => [
            //     'type' => MenuTree::class,
            //     'options' => [
            //         'model' => MenuItem::class
            //         'textField' => 'name'
            //     ]
            // ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'name' => 'required',
            'machineName' => 'required|unique:menus,machineName'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'machineName.required' => 'Machine Name is required',
            'machineName.unique' => 'Machine name already exists'
        ];
    }

    public function getRouteKeyName()
    {
        return 'machineName';
    }

    /**
     * A menu can have several items
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): Relation
    {
        return $this->hasMany(MenuItem::class)->orderBy('weight');
    }

    public function jsGridFields()
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
                'url' => $this::transformAdminUri('edit', [$this], true)
            ],
            'items' => [
                'title' => 'Items',
                'url' => $this::transformAdminUri('editItems', [$this], true)
            ]
        ];
    }

    /**
     * Get the direct children of this menu, uses Menus facade for better caching
     * @return Collection
     */
    public function getRootItems()
    {
        return \Menus::menuRootItems($this);
    }

    /**
     * Get the direct active children of this menu. Uses menus facade for better caching.
     * @return Collection
     */
    public function getActiveRootItems()
    {
        return \Menus::menuActiveRootItems($this);
    }

    /**
     * Finds a menu by its name
     * @param  string $machineName
     * @return Menu
     */
    public static function findByName(string $machineName)
    {
        return \Menus::menuByName($machineName);
    }

    /**
     * Returns the next weight
     * @return integer
     */
    public function getRootNextWeight()
    {
        return \Menus::menuRootNextWeight($this);
    }

    /**
     * @inheritDoc
     */
    public static function adminEditItemsUri()
    {
        return static::routeSlug().'/{'.static::routeSlug().'}/items';
    }
}
