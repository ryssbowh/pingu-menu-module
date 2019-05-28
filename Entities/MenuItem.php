<?php

namespace Pingu\Menu\Entities;

use Illuminate\Validation\Validator;
use Pingu\Core\Contracts\APIableModel as APIableModelContract;
use Pingu\Core\Contracts\HasChildren as HasChildrenContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Traits\APIableModel;
use Pingu\Core\Traits\HasChildren;
use Pingu\Forms\Contracts\FormableModel as FormableModelContract;
use Pingu\Forms\Fields\Boolean;
use Pingu\Forms\Fields\Model;
use Pingu\Forms\Fields\Text;
use Pingu\Forms\Fields\Url;
use Pingu\Forms\FormModel;
use Pingu\Forms\Renderers\Hidden;
use Pingu\Forms\Traits\FormableModel;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Permissions\Entities\Permission;
use Route;

class MenuItem extends BaseModel implements HasChildrenContract, FormableModelContract, APIableModelContract
{
    use HasChildren, FormableModel, APIableModel;

    protected $attributes = [
        'url' => ''
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
    
    protected $visible = ['id', 'weight', 'active', 'class','url', 'name', 'menu', 'permission'];

    protected $fillable = ['weight', 'active', 'class','url', 'name', 'menu', 'permission'];

    public static $editFields = [ 'name', 'url', 'class', 'active','menu', 'permission'];

    public static $addFields = [ 'name', 'url', 'class', 'active','menu', 'permission'];

    public static $fieldDefinitions = [
        'name' => [
            'type' => Text::class
        ],
        'class' => [
            'type' => Text::class
        ],
        'active' => [
            'type' => Boolean::class
        ],
        'url' => [
            'type' => Url::class
        ],
        'menu' => [
            'type' => Model::class,
            'model' => Menu::class,
            'textField' => 'name',
            'renderer' => Hidden::class
        ],
        'permission' => [
            'type' => Model::class,
            'model' => Permission::class,
            'textField' => 'name',
            'noValueLabel' => 'No permission'
        ]
    ];

    public static $validationRules = [
        'name' => 'required',
        'menu' => 'required',
        'active' => 'boolean',
        'url' => 'nullable',
        'menu' => 'required|exists:menus,id',
        'permission' => 'nullable|exists:permissions,id',
        'weight' => 'nullable'
    ];

    public static $validationMessages = [
        'name.required' => 'Name is required',
        'menu.required' => 'Menu is required'
    ];

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
     * Does the logged in user have the permission to see this
     * @return bool
     */
    public function isUserVisible()
    {
        if(!$this->permission) return true;
        $user = \Auth::user();
        if(!$user) return false;
        return $user->hasPermissionTo($this->permission);
    }

    /**
     * Does this have at least one child that the current user can see.
     * @return boolean
     */
    public function hasVisibleChild()
    {
        if($this->children->isEmpty()) return false;
        foreach($this->children as $child){
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
        if($this->hasLink()){
            return ($this->isUserVisible() or $this->hasVisibleChild());
        }
        return ($this->isUserVisible() and $this->hasVisibleChild());
    }

    public function hasLink()
    {
        return !empty($this->generateUri());
    }

    /**
     * Generates the link for this item. Will return a span element is no uri is
     * set for this item or if the user doesn't have the permission to see it.
     * @return [type] [description]
     */
    public function generateLink()
    {
        if($this->url and $this->isUserVisible()){
            return '<a href="'.$this->generateUri().'">'.$this->name.'</a>';
        }
        return '<span>'.$this->name.'</span>';
    }

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

    protected function modifyValidator(Validator $validator, array $values, array $fields)
    {
        if(isset($values['url'])){
            $validator->after(function($validator) use ($values){
                $url = $values['url'];
                if($url and substr($url, 0, 4) != 'http' and !route_exists($url)){
                    $validator->errors()->add('url', 'This url doesn\'t exists');
                }
            });
        }
    }

    public function save(array $options = [])
    {
        if(is_null($this->weight)){
            $this->weight = $this->menu->getRootNextWeight();
        }
        return parent::save();
    }

    public static function apiCreateUri()
    {
        return static::apiStoreUri().'/create';
    }

    public static function apiStoreUri()
    {
        return Menu::routeSlug().'/{'.Menu::routeSlug().'}/'.static::routeSlugs();
    }

    public static function apiDeleteUri()
    {
        return Menu::routeSlugs().'/'.static::routeSlugs().'/{'.static::routeSlug().'}';
    }

    public static function apiEditUri()
    {
        return static::apiDeleteUri().'/edit';
    }

    public static function apiUpdateUri()
    {
        return static::apiDeleteUri();
    }

    public static function apiPatchUri()
    {
        return Menu::routeSlugs().'/'.static::routeSlugs();
    }
}
