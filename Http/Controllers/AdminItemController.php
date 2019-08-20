<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Support\Collection;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\AdminModelController;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class AdminItemController extends AdminModelController
{
	use ItemController;

	/**
	 * @inheritDoc
	 */
	protected function onStoreSuccess(BaseModel $item)
	{
		return redirect(Menu::makeUri('editItems', $item->menu, adminPrefix()));
	}

	/**
	 * @inheritDoc
	 */
	protected function onUpdateSuccess(BaseModel $item)
	{
		return redirect(Menu::makeUri('editItems', $item->menu, adminPrefix()));
	}

	/**
	 * @inheritDoc
	 */
	protected function onDeleteSuccess(BaseModel $item)
	{
		return redirect(Menu::makeUri('editItems', $item->menu, adminPrefix()));
	}

	/**
	 * @inheritDoc
	 */
	protected function onPatchSuccess(Collection $items)
	{
		return redirect(Menu::makeUri('editItems', $items[0]->menu, adminPrefix()));
	}
}
