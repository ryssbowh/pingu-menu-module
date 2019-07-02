<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Jsgrid\Http\Controllers\JsGridModelController;
use Pingu\Menu\Entities\Menu;

class MenuJsGridController extends JsGridModelController
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
        return \Auth::user()->can('edit menus');
    }

    /**
     * @inheritDoc
     */
    protected function canDelete()
    {
        return \Auth::user()->can('delete menus');
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
        $options['canSeeAddLink'] = \Auth::user()->can('add menus');
        $options['addLink'] = '/admin/'.Menu::routeSlugs().'/create';
        
        return view('pages.listModel-jsGrid', $options);
    }

}
