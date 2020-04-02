<?php

namespace Pingu\Menu\Entities;

use Illuminate\Database\Eloquent\Relations\Relation;
use Pingu\Core\Contracts\HasItemsContract;
use Pingu\Core\Contracts\RenderableContract;
use Pingu\Core\Contracts\RendererContract;
use Pingu\Core\Traits\Models\HasMachineName;
use Pingu\Core\Traits\RendersWithRenderer;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Support\Fields\TextInput;
use Pingu\Forms\Support\Types\Text;
use Pingu\Jsgrid\Fields\Text as JsGridText;
use Pingu\Menu\Entities\Policies\MenuPolicy;
use Pingu\Menu\Events\MenuCacheChanged;
use Pingu\Menu\Renderers\MenuRenderer;

class Menu extends Entity implements HasItemsContract, RenderableContract
{
    use HasMachineName, RendersWithRenderer;

    protected $dispatchesEvents = [
        'saved' => MenuCacheChanged::class,
        'deleted' => MenuCacheChanged::class
    ];

    protected $fillable = ['name', 'machineName'];

    protected $casts = [
        'deletable' => 'boolean'
    ];

    public $adminListFields = ['name'];

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'machineName';
    }

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    public function getRenderer(): RendererContract
    {
        return new MenuRenderer($this);
    }
}
