<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AjaxModelController;
use Pingu\Forms\Support\Form;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

trait ItemController
{

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return MenuItem::class; 
    }

    /**
	 * @inheritDoc
	 */
    protected function getStoreUri()
	{
		$menu = $this->routeParameters[0];
		return MenuItem::makeUri('store', $menu, adminPrefix());
	}

	/**
	 * @inheritDoc
	 */
	protected function afterCreateFormCreated(Form $form){
		$menu = $this->routeParameters[0];
		$form->setFieldValue('menu', $menu->id);
	}
}
