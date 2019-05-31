<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Contracts\AjaxModelController as AjaxModelControllerContract;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\AjaxModelController;
use Pingu\Menu\Entities\Menu;

class AjaxMenuController extends BaseController implements AjaxModelControllerContract
{
    use AjaxModelController;
    
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Menu::class;
    }
}
