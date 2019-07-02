<?php

namespace Pingu\Menu\Http\Controllers;

use Auth;
use ContextualLinks;
use Illuminate\Http\Request;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AdminModelController;
use Pingu\Forms\FormModel;
use Pingu\Forms\Renderers\Hidden;
use Pingu\Jsgrid\Traits\Controllers\JsGrid;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class MenuController extends AdminModelController
{
    public function editItems(Request $request, Menu $menu)
    {
        ContextualLinks::addModelLinks($menu);

    	return view('menu::edit-items')->with([
    		'menu' => $menu, 
    		'items' => $menu->getRootItems(),
            'addItemUri' => MenuItem::transformAjaxUri('create', [$menu], true),
            'deleteItemUri' => MenuItem::getAjaxUri('delete', true),
            'editItemUri' => MenuItem::getAjaxUri('edit', true),
            'patchItemsUri' => MenuItem::getAjaxUri('patch', true)
    	]);
    }

    /**
     * @inheritDoc
     */
    public function onSuccessfullStore(BaseModel $menu)
    {
        return redirect()->route('menu.admin.menus');
    }

    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return Menu::class;
    }
}
