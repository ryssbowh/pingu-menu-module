<?php

namespace Pingu\Menu\Http\Controllers;

use Pingu\Entity\Http\Controllers\AjaxEntityController;
use Pingu\Entity\Support\Entity;
use Pingu\Forms\Support\Form;

class MenuItemAjaxController extends AjaxEntityController
{
    protected function afterEditFormCreated(Form $form, Entity $entity)
    {
        $form->removeElement('weight');
    }

    protected function afterCreateFormCreated(Form $form, Entity $entity)
    {
        $menu = $this->routeParameter('menu');
        $form->getElement('menu')->setValue($menu->id);
        $form->removeElement('weight');
    }
}
