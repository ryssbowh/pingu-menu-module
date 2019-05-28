<?php

namespace Pingu\Menu\Http\Controllers;

use ContextualLinks;
use Illuminate\Http\Request;
use Pingu\Core\Contracts\ModelController as ModelControllerContract;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\ModelController;
use Pingu\Forms\FormModel;
use Pingu\Forms\Renderers\Hidden;
use Pingu\Jsgrid\Contracts\JsGridController as JsGridControllerContract;
use Pingu\Jsgrid\Traits\JsGridController;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;
use Auth;

class MenuController extends BaseController implements ModelControllerContract, JsGridControllerContract
{
    use ModelController, JsGridController;

    public function editItems(Request $request, Menu $menu)
    {
        ContextualLinks::addModelLinks($menu);

    	return view('menu::edit-items')->with([
    		'menu' => $menu, 
    		'items' => $menu->getRootItems(),
            'addItemUri' => MenuItem::transformApiUri('create', [$menu->id], true),
            'deleteItemUri' => MenuItem::getApiUri('delete', true),
            'editItemUri' => MenuItem::getApiUri('edit', true),
            'patchItemsUri' => MenuItem::getApiUri('patch', true)
    	]);
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
