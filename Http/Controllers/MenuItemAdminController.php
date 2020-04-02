<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Support\Collection;
use Pingu\Entity\Http\Controllers\AdminEntityController;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Support\Form;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class MenuItemAdminController extends AdminEntityController
{
    protected function afterCreateFormCreated(Form $form, Entity $entity)
    {
        $menu = $this->routeParameter('menu');
        $form->getElement('menu')->setValue($menu->id);
    }

    /**
     * @inheritDoc
     */
    protected function onStoreSuccess(Entity $item)
    {
        return redirect(Menu::uris()->make('editItems', $item->menu, adminPrefix()));
    }

    /**
     * @inheritDoc
     */
    protected function onUpdateSuccess(Entity $item)
    {
        return redirect(Menu::uris()->make('editItems', $item->menu, adminPrefix()));
    }

    /**
     * @inheritDoc
     */
    protected function onDeleteSuccess(Entity $item)
    {
        return redirect(Menu::uris()->make('editItems', $item->menu, adminPrefix()));
    }

    /**
     * @inheritDoc
     */
    protected function onPatchSuccess(Entity $entity, Collection $items)
    {
        return redirect(Menu::uris()->make('editItems', $items[0]->menu, adminPrefix()));
    }
}
