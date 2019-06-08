<?php
namespace Pingu\Menu\Facades;

use Illuminate\Support\Facades\Facade;

class Menus extends Facade {

	protected static function getFacadeAccessor() {

		return 'menu.menus';

	}

}