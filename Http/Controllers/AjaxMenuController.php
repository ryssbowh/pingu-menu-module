<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Contracts\Controllers\HandlesAjaxModelContract;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\HandlesAjaxModel;
use Pingu\Menu\Entities\Menu;

class AjaxMenuController extends BaseController implements HandlesAjaxModelContract
{
    use HandlesAjaxModel;
    
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Menu::class;
    }
}
