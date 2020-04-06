<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Entity\Support\Entity;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Forms\Support\Form;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class MenuAdminController extends AdminEntityController
{
    public function editItems(Request $request, Menu $menu)
    {
        \ContextualLinks::addFromObject($menu);

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

    /**
     * @inheritDoc
     */
    public function onStoreSuccess(Entity $menu)
    {
        return redirect(Menu::uris()->make('editItems', $menu, adminPrefix()));
    }

    protected function afterEditFormCreated(Form $form, Entity $entity)
    {
        $form->getElement('machineName')->option('disabled', true);
    }
}
