<?php

namespace Pingu\Menu;

use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Pingu\Menu\Exceptions\MenuDoesntExists;
use Pingu\Menu\Exceptions\MenuItemDoesntExists;

class Menus
{
	/**
	 * Gets a menu by its id or name
	 * @param  int|string $nameOrId
	 * @return Menu
	 */
	public function menu($nameOrId)
	{
		if(is_int($nameOrId)){
			return $this->menuById($nameOrId);
		}
		return $this->menuByName($nameOrId);
	}

	/**
	 * Gets a menu by its id
	 * @param  int    $id
	 * @return Menu
	 * @throws MenuDoesntExists
	 */
	public function menuById(int $id)
	{
		$menu = $this->resolveMenuCache()->where('id', $id)->first();
		if(is_null($menu)){
			throw new MenuDoesntExists("Couldn't find a menu for id $id");
		}
		return $menu;
	}

	/**
	 * gets a menu by its name
	 * @param  string $name
	 * @return Menu
	 * @throws MenuDoesntExists
	 */
	public function menuByName(string $name)
	{
		$menu = $this->resolveMenuCache()->where('machineName', $name)->first();
		if(is_null($menu)){
			throw new MenuDoesntExists("Couldn't find a menu for machine name $name");
		}
		return $menu;
	}

	/**
	 * Gets an item by its id
	 * @param  int    $id
	 * @return MenuItem
	 * @throws MenuItemDoesntExists
	 */
	public function itemById(int $id)
	{
		$item = $this->resolveItemsCache()->where('id', $id)->first();
		if(is_null($item)){
			throw new MenuItemDoesntExists("Couldn't find an item for id $id");
		}
		return $menu;
	}

	/**
	 * Gets an item by its machine name
	 * @param  int    $id
	 * @return MenuItem
	 * @throws MenuItemDoesntExists
	 */
	public function itemByName(string $name)
	{
		$item = $this->resolveItemsCache()->where('machineName', $name)->first();
		if(is_null($item)){
			throw new MenuItemDoesntExists("Couldn't find an item with machine name $name");
		}
		return $item;
	}

	/**
	 * Returns all MenuItem that are direct children of $menu
	 * @param  Menu|int|string   $menu
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
	 * @param  Menu|int|string   $menu
	 * @return Collection
	 */
	public function menuActiveRootItems($menu)
	{
		return $this->menuRootItems($menu)
			->where('active', 1);
	}

	/**
	 * Return the next item weight for a menu (root level)
	 * @param  Menu|int|string   $menu
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
	 * @param  MenuItem|id $item
	 * @return Collection
	 */
	public function itemChildren($item)
	{
		$item = $this->resolveItem($item);
		return $this->resolveItemsCache()->where('parent_id', $item->id)->sortBy('weight');
	}

	/**
	 * Gets all active children for an item
	 * @param  MenuItem|id $item
	 * @return Collection
	 */
	public function itemActiveChildren($item)
	{
		return $this->itemChildren($item)->where('active', 1);
	}

	/**
	 * Resolve a menu item identifier which can be a MenuItem or an id
	 * @param  MenuItem|int $item
	 * @return MenuItem
	 */
	protected function resolveItem($item)
	{
		if($item instanceof MenuItem) return $item;
		return $this->itemById($item);
	}

	/**
	 * Resolve a menu identifer which can be a Menu instance, an id or a machineName
	 * @param  Menu|int|string $menu
	 * @return Menu
	 */
	protected function resolveMenu($menu)
	{
		if($menu instanceof Menu) return $menu;
		return $this->menu($menu);
	}

	protected function resolveMenuCache()
	{
		return \Cache::rememberForever(config('menu.cache-keys.menus'), function(){
			return Menu::get();
		});
	}

	protected function resolveItemsCache()
	{
		return \Cache::rememberForever(config('menu.cache-keys.items'), function(){
			return MenuItem::get();
		});
	}

	public function forgetMenusCache()
	{
		\Cache::forget(config('menu.cache-keys.menus'));
	}

	public function forgetItemsCache()
	{
		\Cache::forget(config('menu.cache-keys.items'));
	}

	public function forgetAllCaches()
	{
		$this->forgetItemsCache();
		$this->forgetMenusCache();
	}

}