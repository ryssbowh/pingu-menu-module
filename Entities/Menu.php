<?php

namespace Pingu\Menu\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
use Pingu\Core\Contracts\Models\HasContextualLinksContract;
use Pingu\Core\Contracts\Models\HasItemsContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasBasicCrudUris;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Text;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Jsgrid\Contracts\Models\JsGridableContract;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Jsgrid\Traits\Models\JsGridable;
use Pingu\Menu\Events\MenuCacheChanged;

class Menu extends BaseModel implements JsGridableContract, HasContextualLinksContract, HasItemsContract
{
    use JsGridable, Formable, HasBasicCrudUris, HasMachineName;

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
                    'label' => 'Name'
                ],
                'attributes' => [
                    'required' => true
                ]
            ],
            'machineName' => [
                'field' => TextInput::class,
                'options' => [
                    'label' => 'Machine Name'
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
                'url' => $this::makeUri('edit', [$this], adminPrefix())
            ],
            'items' => [
                'title' => 'Items',
                'url' => $this::makeUri('editItems', [$this], adminPrefix())
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
    public static function editItemsUri()
    {
        return static::routeSlug().'/{'.static::routeSlug().'}/items';
    }
}
