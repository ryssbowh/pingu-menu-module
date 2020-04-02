<?php

namespace Pingu\Menu\Renderers;

use Pingu\Core\Support\Renderer;
use Pingu\Forms\Support\ClassBag;
use Pingu\Menu\Entities\Menu;

class MenuRenderer extends Renderer
{
    public function __construct(Menu $menu)
    {
        parent::__construct($menu);
    }

    /**
     * @inheritDoc
     */
    public function identifier(): string
    {
        return 'menu';
    }

    /**
     * @inheritDoc
     */
    public function objectIdentifier(): string
    {
        return $this->object->machineName;
    }

    /**
     * @inheritDoc
     */
    public function getHookData(): array
    {
        return [$this->object->machineName, $this->object, $this];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultData(): array
    {
        return [
            'menu' => $this->object,
            'classes' => new ClassBag(['menu', 'menu-'.$this->object->machineName]),
            'items' => \Menus::build($this->object)
        ];
    }

    /**
     * @inheritDoc
     */
    public function getFinalData(): array
    {
        return [
            'menu' => $this->object,
            'classes' => $this->getData('classes')->get(true)
            'items' => $this->getData('items')
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDefaultViews(): array
    {
        return [
            'menus.menu-'.$this->object->machineName,
            'menus.menu',
            'menu@menu'
        ];
    }
}