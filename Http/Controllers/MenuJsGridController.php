<?php

namespace Pingu\Menu\Http\Controllers;

use Auth;
use ContextualLinks;
use Illuminate\Http\Request;
use Pingu\Core\Entities\BaseModel;
use Pingu\Forms\FormModel;
use Pingu\Forms\Renderers\Hidden;
use Pingu\Jsgrid\Http\Controllers\JsGridController;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class MenuJsGridController extends JsGridController
{
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
