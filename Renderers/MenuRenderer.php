<?php

namespace Pingu\Menu\Renderers;

use Illuminate\Support\Collection;
use Pingu\Core\Support\Renderers\ObjectRenderer;
use Pingu\Forms\Support\ClassBag;
use Pingu\Menu\Entities\Menu;

class MenuRenderer extends ObjectRenderer
{      
    public function __construct(Menu $menu)
    {
        parent::__construct($menu);
    }

    /**
     * @inheritDoc
     */
    public function viewFolder(): string
    {
        return 'menus';
    }

    /**
     * @inheritDoc
     */
    public function getHookName(): string
    {
        return 'menu';
    }

    /**
     * @inheritDoc
     */
    protected function viewIdentifier(): string
    {
        return 'menu';
    }
    
    /**
     * @inheritDoc
     */
    public function getDefaultData(): Collection
    {
        return collect([
            'menu' => $this->object,
            'classes' => new ClassBag(['menu', 'menu-'.$this->object->machineName]),
            'items' => \Menus::build($this->object)
        ]);
    }
}