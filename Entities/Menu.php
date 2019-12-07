<?php

namespace Pingu\Menu\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\Menu\Entities\Policies\MenuPolicy;
use Pingu\Core\Contracts\Models\HasItemsContract;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Entity\Entities\Entity;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Text;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Menu\Events\MenuCacheChanged;

class Menu extends Entity implements HasItemsContract
{
    use HasMachineName;

    protected $dispatchesEvents = [
        'saved' => MenuCacheChanged::class,
        'deleted' => MenuCacheChanged::class
    ];

    protected $fillable = ['name', 'machineName'];

    protected $casts = [
        'deletable' => 'boolean'
    ];

    public $adminListFields = ['name'];

    public function getRouteKeyName()
    {
        return 'machineName';
    }

    public function getPolicy(): string
    {
        return MenuPolicy::class;
    }

    /**
     * A menu can have several items
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): Relation
    {
        return $this->hasMany(MenuItem::class)->orderBy('weight');
    }

    /**
     * Get the direct children of this menu, uses Menus facade for better caching
     * 
     * @return Collection
     */
    public function getRootItems()
    {
        return \Menus::menuRootItems($this);
    }

    /**
     * Get the direct active children of this menu. Uses menus facade for better caching.
     * 
     * @return Collection
     */
    public function getActiveRootItems()
    {
        return \Menus::menuActiveRootItems($this);
    }

    /**
     * Returns the next weight
     *
     * @return integer
     */
    public function getRootNextWeight()
    {
        return \Menus::menuRootNextWeight($this);
    }
}
