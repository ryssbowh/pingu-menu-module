<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Entity\Http\Controllers\EntityCrudContextController;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class MenuController extends EntityCrudContextController
{
    public function editItems(Request $request, Menu $menu)
    {
        \ContextualLinks::addObjectActions($menu, 'admin');

        return view('pages.menu.editItems')->with(
            [
            'menu' => $menu, 
            'items' => $menu->getRootItems(),
            'addItemUri' => MenuItem::uris()->make('create', [$menu], adminPrefix()),
            'deleteItemUri' => MenuItem::uris()->get('delete', adminPrefix()),
            'editItemUri' => MenuItem::uris()->get('edit', adminPrefix()),
            'patchItemsUri' => MenuItem::uris()->get('patch', adminPrefix())
            ]
        );
    }
}
