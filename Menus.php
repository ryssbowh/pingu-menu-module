<?php

namespace Pingu\Menu;

use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Menu\Exceptions\MenuDoesntExists;
use Pingu\Menu\Exceptions\MenuItemDoesntExists;
use Pingu\Permissions\Contracts\Role;

class Menus
{
    /**
     * Gets a menu by its id or name
     * 
     * @param int|string $nameOrId
     * 
     * @return Menu
     */
    public function menu($nameOrId)
    {
        if (is_int($nameOrId)) {
            return $this->menuById($nameOrId);
        }
        return $this->menuByName($nameOrId);
    }

    /**
     * Gets a menu item by its id or name
     * 
     * @param int|string $nameOrId
     * 
     * @return Menu
     */
    public function item($nameOrId)
    {
        if (is_int($nameOrId)) {
            return $this->itemById($nameOrId);
        }
        return $this->itemByName($nameOrId);
    }

    /**
     * Gets a menu by its id
     * 
     * @param int $id
     * 
     * @throws MenuDoesntExists
     * @return Menu
     */
    public function menuById(int $id)
    {
        $menu = $this->resolveMenuCache()->where('id', $id)->first();
        if (is_null($menu)) {
            throw new MenuDoesntExists("Couldn't find a menu for id $id");
        }
        return $menu;
    }

    /**
     * gets a menu by its name
     * 
     * @param string $name
     *
     * @throws MenuDoesntExists
     * @return Menu
     */
    public function menuByName(string $name)
    {
        $menu = $this->resolveMenuCache()->where('machineName', $name)->first();
        if (is_null($menu)) {
            throw new MenuDoesntExists("Couldn't find a menu for machine name $name");
        }
        return $menu;
    }

    /**
     * Gets an item by its id
     * 
     * @param int $id
     * 
     * @throws MenuItemDoesntExists
     * @return MenuItem
     */
    public function itemById(int $id)
    {
        $item = $this->resolveItemsCache()->where('id', $id)->first();
        if (is_null($item)) {
            throw new MenuItemDoesntExists("Couldn't find an item for id $id");
        }
        return $menu;
    }

    /**
     * Gets an item by its machine name
     * 
     * @param int $id
     * 
     * @return MenuItem
     * @throws MenuItemDoesntExists
     */
    public function itemByName(string $name)
    {
        $item = $this->resolveItemsCache()->where('machineName', $name)->first();
        if (is_null($item)) {
            throw new MenuItemDoesntExists("Couldn't find an item with machine name $name");
        }
        return $item;
    }

    /**
     * Returns all MenuItem that are direct children of $menu
     * 
     * @param Menu|int|string $menu
     * 
     * @return Collection
     */
    public function menuRootItems($menu)
    {
        $menu = $this->resolveMenu($menu);
        return $this->resolveItemsCache()
            ->where('menu_id', $menu->id)
            ->where('parent_id', null)
            ->sortBy('weight');
    }

    /**
     * Returns all MenuItem that are direct children of $menu and active
     * 
     * @param Menu|int|string $menu
     * 
     * @return Collection
     */
    public function menuActiveRootItems($menu)
    {
        return $this->menuRootItems($menu)
            ->where('active', 1);
    }

    /**
     * Return the next item weight for a menu (root level)
     * 
     * @param Menu|int|string $menu
     * 
     * @return int
     */
    public function menuRootNextWeight($menu)
    {
        $menu = $this->resolveMenu($menu);
        $last = $this->menuRootItems($menu)->last();
        return ($last ? $last->weight+1 : 0);
    }

    /**
     * Gets all children for an item
     * 
     * @param MenuItem|id $item
     * 
     * @return Collection
     */
    public function itemChildren($item)
    {
        $item = $this->resolveItem($item);
        return $this->resolveItemsCache()->where('parent_id', $item->id)->sortBy('weight');
    }

    /**
     * Gets all active children for an item
     * 
     * @param MenuItem|id $item
     * 
     * @return Collection
     */
    public function itemActiveChildren($item)
    {
        return $this->itemChildren($item)->where('active', 1);
    }

    /**
     * Resolve a menu item identifier which can be a MenuItem or an id
     * 
     * @param MenuItem|int $item
     * 
     * @return MenuItem
     */
    public function resolveItem($item)
    {
        if (is_null($item)) {
            return null;
        }
        if ($item instanceof MenuItem) {
            return $item;
        }
        return $this->item($item);
    }

    /**
     * Resolve a menu identifer which can be a Menu instance, an id or a machineName
     * 
     * @param Menu|int|string $menu
     * 
     * @return Menu
     */
    public function resolveMenu($menu)
    {
        if (is_null($menu)) {
            return null;
        }
        if ($menu instanceof Menu) { return $menu;
        }
        return $this->menu($menu);
    }

    /**
     * Get the menus from cache
     * 
     * @return Collection
     */
    protected function resolveMenuCache()
    {
        if (config('menu.use-cache')) {
            return \Cache::rememberForever(
                config('menu.cache-keys.menus'), function () {
                    return Menu::get();
                }
            );
        }
        return Menu::get();
    }
    /**
     * Get the menu items from cache
     * 
     * @return Collection
     */
    protected function resolveItemsCache()
    {
        if (config('menu.use-cache')) {
            return \Cache::rememberForever(
                config('menu.cache-keys.items'), function () {
                    return MenuItem::get();
                }
            );
        }
        return MenuItem::get();
    }

    /**
     * Forget the menu cache
     */
    public function forgetMenusCache()
    {
        \Cache::forget(config('menu.cache-keys.menus'));
    }

    /**
     * Forget the menu cache
     */
    public function forgetItemsCache()
    {
        \Cache::forget(config('menu.cache-keys.items'));
    }

    /**
     * Forget the meu built cache
     */
    public function forgetBuiltMenuCache()
    {
        \ArrayCache::forget(config('menu.cache-keys.built'));
    }

    /**
     * Forget all caches related to menus
     */
    public function forgetAllCaches()
    {
        $this->forgetItemsCache();
        $this->forgetMenusCache();
        $this->forgetBuiltMenuCache();
    }

    /**
     * Builds items for a role
     * 
     * @param $items
     * @param Role  $role
     * 
     * @return array
     */
    protected function buildItems($items, Role $role): array
    {
        $out = [];
        foreach ($items as $item) {
            if ($item->isVisible($role)) {
                $array = $item->toArray();
                $array['item'] = $item;
                $array['link'] = $item->generateLink($role);
                $children = $item->getActiveChildren();
                $array['hasChildren'] = false;
                if (!$children->isEmpty()) {
                    $array['hasChildren'] = true;
                    $array['children'] = $this->buildItems($children, $role);
                }
                $out[$item->machineName] = $array;
            }
        }
        return $out;
    }

    /**
     * Builds a menu for a role. Saves it in cache
     * 
     * @param Menu $menu 
     * @param Role $role 
     * 
     * @return array
     */
    public function buildForRole(Menu $menu, Role $role): array
    {
        if (config('menu.use-cache')) {
            $key = config('menu.cache-keys.built').'.'.$role->id;
            $_this = $this;
            return \ArrayCache::rememberForever(
                $key, function () use ($menu, $role, $_this) {
                    $items = $_this->menuActiveRootItems($menu);
                    return $_this->buildItems($items, $role);
                }
            );
        }

        $items = $this->menuActiveRootItems($menu);
        return $this->buildItems($items, $role);
    }

    /**
     * Build a menu for many roles
     * 
     * @param Menu $menu
     * @param $roles
     * 
     * @return array        
     */
    public function buildForRoles(Menu $menu, $roles): array
    {
        $out = [];
        foreach ($roles as $role) {
            $out = array_merge_recursive($out, $this->buildForRole($menu, $role));
        }
        return $out;
    }

    /**
     * Builds a menu
     * 
     * @param Menu|string|id $menu
     * 
     * @return array
     */
    public function build($menu): array
    {
        $menu = $this->resolveMenu($menu);
        $user = \Auth::user();
        $build = $this->buildForRoles($menu, $user ? $user->roles : \Permissions::guestRole());
        $currentUri = '/'.request()->path();
        return $this->resolveActiveItems($build, $currentUri);
    }

    /**
     * Calculate items that are active or that have active items
     * 
     * @param array  $build
     * @param string $uri
     * 
     * @return array
     */
    protected function resolveActiveItems(array $build, string $uri)
    {
        foreach ($build as $key => $array) {
            if ($array['hasChildren']) {
                $array['children'] = $this->resolveActiveItems($array['children'], $uri);
            }
            $array['active'] = false;
            if ($uri == $array['uri']) {
                $array['active'] = true;
            }
            $array['hasActiveChild'] = false;
            if ($array['hasChildren']) {
                foreach ($array['children'] as $child) {
                    if ($child['active']) {
                        $array['hasActiveChild'] = true;
                    }
                }
            }
            $build[$key] = $array;
        }
        return $build;
    }
}