<?php

namespace Pingu\Menu\Http\Controllers;

use Pingu\Core\Http\Controllers\AjaxModelController;
use Pingu\Forms\Support\Form;
use Pingu\Menu\Entities\MenuItem;

class AjaxItemController extends AjaxModelController
{
	use ItemController;

	/**
	 * @inheritDoc
	 */
	protected function afterCreateFormCreated(Form $form){
		parent::afterCreateFormCreated($form);
		$menu = $this->routeParameters[0];
		$form->setFieldValue('menu', $menu->id);
	}
}
