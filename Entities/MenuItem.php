<?php

namespace Pingu\Menu\Entities;

use Illuminate\Support\Str;
use Pingu\Core\Contracts\Models\HasChildrenContract;
use Pingu\Core\Traits\Models\HasChildren;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Core\Traits\Models\HasWeight;
use Pingu\Entity\Entities\Entity;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\Policies\MenuItemPolicy;
use Pingu\Menu\Events\MenuItemCacheChanged;
use Pingu\Permissions\Entities\Permission;
use Pingu\User\Entities\Role;
use Route;

class MenuItem extends Entity implements HasChildrenContract
{
    use HasChildren, HasWeight, HasMachineName;

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

    protected $with = ['children'];

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($item) {
                $item->generateMachineName();
                if(!$item->weight) { $item->weight = $item->menu->getRootNextWeight();
                }
            }
        );
    }

    public function getPolicy(): string
    {
        return MenuItemPolicy::class;
    }

    /**
     * A item belongs to one menu
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * A item has one viewing permission
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * gets this item's children. uses the facade for caching
     * 
     * @return Collection
     */
    public function getChildren()
    {
        return \Menus::itemChildren($this);
    }

    /**
     * Gets this item's active children. Uses the facade for caching
     * 
     * @return Collection
     */
    public function getActiveChildren()
    {
        return \Menus::itemActiveChildren($this);
    }

    public function isActive()
    {
        $uri = trim(request()->path(), '/');
        if($uri == trim($this->generateUri(), '/')) {
            return true;
        }
        return false;
    }

    public function hasActiveChild()
    {
        foreach ($this->children as $child) {
            if ($child->isActive() or $child->hasActiveChild()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Does the logged in user have the permission to see this
     *
     * @return bool
     */
    protected function isUserVisible($permissionable)
    {
        if (!$this->permission_id) {
            return true;
        }
        return $permissionable->hasPermissionTo($this->permission_id);
    }

    /**
     * Does this item have active children
     *
     * @return boolean
     */
    public function hasActiveChildren()
    {
        return !$this->getActiveChildren()->isEmpty();
    }

    /**
     * Does this have at least one child that the current user can see.
     *
     * @return boolean
     */
    protected function hasVisibleChild($permissionable)
    {
        if (!$this->hasActiveChildren()) { return false;
        }
        foreach ($this->getActiveChildren() as $child) {
            if ($child->isVisible($permissionable)) { return true;
            }
        }
        return false;
    }

    /**
     * Is this item visible to the current user, and if not, is one of its children visible
     * 
     * @return boolean
     */
    public function isVisible($permissionable = null)
    {
        $permissionable = \Permissions::resolvePermissionable($permissionable);
        $visibleChild = $this->hasVisibleChild($permissionable);
        $userVisible = $this->isUserVisible($permissionable);
        return ($visibleChild or $userVisible);
    }

    /**
     * Does this item have a valid link
     * 
     * @return boolean
     */
    public function hasValidLink()
    {
        return !empty($this->generateUri());
    }

    /**
     * Generates the link for this item. Will return a span element is no uri is
     * set for this item or if the user doesn't have the permission to see it.
     * 
     * @return string
     */
    public function generateLink($permissionable = null)
    {
        $permissionable = \Permissions::resolvePermissionable($permissionable);
        if ($this->url and $this->isUserVisible($permissionable)) {
            return '<a href="'.$this->generateUri().'">'.$this->name.'</a>';
        }
        return '<span>'.$this->name.'</span>';
    }

    /**
     * Generates the uri for this item, do not check permission.
     * 
     * @return string|false
     */
    public function generateUri()
    {
        $url = $this->url;
        if (!$url or substr($url, 0, 4) == 'http' or substr($url, 0, 1) == '/') {
            return $url;
        }
        if ($route = Route::getRoutes()->getByName($url)) {
            return '/'.$route->uri();
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['uri'] = $this->generateUri();
        return $array;
    }

    /**
     * @inheritDoc
     */
    public static function firstOrCreate(array $attributes, array $values, Menu $menu, MenuItem $parent = null)
    {
        $item = static::where($attributes)->first();
        if(!$item) {
            $item = new static;
            $item->fill($attributes);
            $item->fill($values);
            $item->menu()->associate($menu);
            if($parent) {
                $item->parent()->associate($parent);
            }
            $item->save();
        }
        return $item;
    }

    /**
     * @inheritDoc
     */
    public static function create(array $values, $menu, $parent = null)
    {
        $item = new static;
        $item->fill($values);
        $menu = \Menus::resolveMenu($menu);
        $parent = \Menus::resolveItem($parent);
        $item->menu()->associate($menu);
        if ($parent) {
            $item->parent()->associate($parent);
        }
        $item->save();
        return $item;
    }

    /**
     * Generate a machine name for this item
     */
    public function generateMachineName()
    {
        $name = Str::kebab($this->name);
        $parent = $this->parent;
        if ($parent) {
            $name = Str::kebab($parent->machineName).'.'.$name;
        } else {
            $name = $this->menu->machineName.'.'.$name;
        }
        $this::unguard();
        $this->machineName = $this->getUniqueMachineName($name);
    }
}
