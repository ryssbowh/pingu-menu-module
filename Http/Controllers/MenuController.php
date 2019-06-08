<?php

namespace Pingu\Menu\Http\Controllers;

use Auth;
use ContextualLinks;
use Illuminate\Http\Request;
use Pingu\Core\Contracts\Controllers\HandlesModelContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\HandlesModel;
use Pingu\Forms\FormModel;
use Pingu\Forms\Renderers\Hidden;
use Pingu\Jsgrid\Contracts\Controllers\JsGridContract;
use Pingu\Jsgrid\Traits\Controllers\JsGrid;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class MenuController extends BaseController implements HandlesModelContract, JsGridContract
{
    use HandlesModel, JsGrid;

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
     * @param  BaseModel $menu
     */
    public function onSuccessfullStore(BaseModel $menu)
    {
        return redirect()->route('menu.admin.menus');
    }

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Menu::class;
    }

    /**
     * @inheritDoc
     */
    protected function canClick()
    {
        return Auth::user()->can('edit menus');
    }

    /**
     * @inheritDoc
     */
    protected function canDelete()
    {
        return Auth::user()->can('delete menus');
    }

    /**
     * @inheritDoc
     */
    protected function canEdit()
    {
        return $this->canClick();
    }

    /**
     * @inheritDoc
     */
    public function index(Request $request)
    {
        $options['jsgrid'] = $this->buildJsGridView($request);
        $options['title'] = str_plural(Menu::friendlyName());
        $options['canSeeAddLink'] = Auth::user()->can('add menus');
        $options['addLink'] = '/admin/'.Menu::routeSlugs().'/create';
        
        return view('pages.listModel-jsGrid', $options);
    }

}
