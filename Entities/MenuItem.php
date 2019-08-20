<?php

namespace Pingu\Menu\Entities;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Pingu\Core\Contracts\Models\HasChildrenContract;
use Pingu\Core\Contracts\Models\HasCrudUrisContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\Models\HasAjaxRoutes;
use Pingu\Core\Traits\Models\HasBasicCrudUris;
use Pingu\Core\Traits\Models\HasChildren;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Core\Traits\Models\HasRouteSlug;
use Pingu\Core\Traits\Models\HasWeight;
use Pingu\Forms\Contracts\Models\FormableContract;
use Pingu\Forms\Support\Fields\Checkbox;
use Pingu\Forms\Support\Fields\ModelSelect;
use Pingu\Forms\Support\Fields\NumberInput;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Traits\Models\Formable;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Menu\Events\MenuItemCacheChanged;
use Pingu\Menu\Exceptions\MenuItemDoesntExists;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;
use Route;

class MenuItem extends BaseModel implements HasChildrenContract, FormableContract, HasCrudUrisContract
{
    use HasChildren, Formable, HasBasicCrudUris, HasWeight, HasMachineName;

    protected $dispatchesEvents = [
        'saved' => MenuItemCacheChanged::class,
        'deleted' => MenuItemCacheChanged::class
    ];

    protected $attributes = [
        'url' => '',
        'active' => 1
    ];

    protected $casts = [
        'active' => 'boolean',
        'deletable' => 'boolean'
    ];
    
    protected $visible = ['id', 'weight', 'active', 'class','url', 'name', 'menu', 'permission'];

    protected $fillable = ['id', 'weight', 'active', 'class','url', 'name', 'menu', 'permission'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($item){
            $item->generateMachineName();
            if(!$item->weight) $item->weight = $item->menu->getRootNextWeight();
        });
    }

    /**
     * @inheritDoc
     */
    public function formAddFields()
    {
        return ['name', 'menu', 'url', 'class', 'active', 'permission', 'weight'];
    }

    /**
     * @inheritDoc
     */
    public function formEditFields()
    {
        return ['name', 'menu', 'url', 'class', 'active', 'permission', 'weight'];
    }

    /**
     * @inheritDoc
     */
    public function fieldDefinitions()
    {
        return [
            'name' => [
                'field' => TextInput::class
            ],
            'class' => [
                'field' => TextInput::class
            ],
            'weight' => [
                'field' => NumberInput::class
            ],
            'active' => [
                'field' => Checkbox::class
            ],
            'url' => [
                'field' => TextInput::class
            ],
            'menu' => [
                'field' => ModelSelect::class,
                'options' => [
                    'model' => Menu::class,
                    'textField' => 'name',
                    'default' => $this->menu
                ]
            ],
            'permission' => [
                'field' => ModelSelect::class,
                'options' => [
                    'model' => Permission::class,
                    'textField' => 'name',
                    'noValueLabel' => 'No permission',
                    'label' => 'Viewing permission'
                ]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationRules()
    {
        return [
            'name' => 'required',
            'menu' => 'required',
            'active' => 'boolean',
            'url' => 'sometimes|valid_url',
            'menu' => 'required|exists:menus,id',
            'permission' => 'nullable|exists:permissions,id',
            'weight' => 'nullable',
            'class' => 'string'
        ];
    }

    /**
     * @inheritDoc
     */
    public function validationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'menu.required' => 'Menu is required',
            'url.valid_url' => 'This url doesn\'t exist'
        ];
    }

    /**
     * A item belongs to one menu
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
    	return $this->belongsTo(Menu::class);
    }

    /**
     * A item has one viewing permission
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * gets this item's children. uses the facade for caching
     * @return Collection
     */
    public function getChildren()
    {
        return \Menus::itemChildren($this);
    }

    /**
     * Gets this item's active children. Uses the facade for caching
     * @return Collection
     */
    public function getActiveChildren()
    {
        return \Menus::itemActiveChildren($this);
    }

    public function isActive()
    {
        $uri = trim(request()->path(), '/');
        if($uri == trim($this->generateUri(),'/')){
            return true;
        }
        return false;
    }

    /**
     * Does the logged in user have the permission to see this
     * @return bool
     */
    public function isUserVisible()
    {
        if($this->permission) return true;
        $model = \Permissions::getPermissionableModel();
        return $model->hasPermissionTo($this->permission);
    }

    /**
     * Does this item have active children
     * @return boolean
     */
    public function hasActiveChildren()
    {
        return !$this->getActiveChildren()->isEmpty();
    }

    /**
     * Does this have at least one child that the current user can see.
     * @return boolean
     */
    public function hasVisibleChild()
    {
        if($this->hasActiveChildren()) return false;
        foreach($this->getChildren() as $child){
            if($child->isVisible()) return true;
        }
        return false;
    }

    /**
     * Is this item visible to the current user, and if not, is one of its children visible
     * @return boolean
     */
    public function isVisible()
    {
        return ($this->isUserVisible() or $this->hasVisibleChild());
    }

    /**
     * Does this item have a link
     * @return boolean
     */
    public function hasLink()
    {
        return !empty($this->generateUri());
    }

    /**
     * Generates the link for this item. Will return a span element is no uri is
     * set for this item or if the user doesn't have the permission to see it.
     * @return string
     */
    public function generateLink()
    {
        if($this->url and $this->isUserVisible()){
            return '<a href="'.$this->generateUri().'">'.$this->name.'</a>';
        }
        return '<span>'.$this->name.'</span>';
    }

    /**
     * Generates the uri for this item, do not check permission.
     * @return string|false
     */
    public function generateUri()
    {
        $url = $this->url;
        if(!$url or substr($url, 0, 4) == 'http' or substr($url, 0, 1) == '/'){
            return $url;
        }
        $route = Route::getRoutes()->getByName($url);
        if($route) return '/'.$route->uri();
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function firstOrCreate(array $attributes, array $values, Menu $menu, MenuItem $parent = null)
    {
        $item = static::where($attributes)->first();
        if(!$item){
            $item = new static;
            $item->fill($attributes);
            $item->fill($values);
            $item->menu()->associate($menu);
            if($parent){
                $item->parent()->associate($parent);
            }
            $item->save();
        }
        return $item;
    }

    /**
     * @inheritDoc
     */
    public static function create(array $values, Menu $menu, MenuItem $parent = null)
    {
        $item = new static;
        $item->fill($values);
        $item->menu()->associate($menu);
        if($parent){
            $item->parent()->associate($parent);
        }
        $item->save();
        return $item;
    }

    /**
     * @inheritDoc
     */
    public static function createUri()
    {
        return static::storeUri().'/create';
    }

    /**
     * @inheritDoc
     */
    public static function storeUri()
    {
        return Menu::routeSlug().'/{'.Menu::routeSlug().'}/'.static::routeSlugs();
    }

    /**
     * Generate a machine name for this item
     */
    public function generateMachineName()
    {
        $name = Str::kebab($this->name);
        $parent = $this->parent;
        if($parent){
            $name = Str::kebab($parent->machineName).'.'.$name;
        }
        else{
            $name = $this->menu->machineName.'.'.$name;
        }
        $this::unguard();
        $this->machineName = $this->getUniqueMachineName($name);
    }
}
