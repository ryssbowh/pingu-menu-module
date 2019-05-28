<?php

namespace Pingu\Menu\Http\Controllers;

use Pingu\Core\Contracts\ApiModelController as ApiModelControllerContract;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\ApiModelController;
use Pingu\Menu\Entities\Menu;

class ApiMenuController extends BaseController implements ApiModelControllerContract
{
    use ApiModelController;
    
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Menu::class;
    }
}
