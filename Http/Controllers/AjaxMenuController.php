<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Http\Controllers\AjaxModelController;
use Pingu\Menu\Entities\Menu;

class AjaxMenuController extends AjaxModelController
{
    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return Menu::class;
    }
}
