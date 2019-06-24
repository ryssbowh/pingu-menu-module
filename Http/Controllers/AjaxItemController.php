<?php

namespace Pingu\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Pingu\Core\Contracts\Controllers\HandlesAjaxModelContract;
use Pingu\Core\Entities\BaseModel;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Core\Traits\Controllers\HandlesAjaxModel;
use Pingu\Forms\Support\Form;
use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

class AjaxItemController extends BaseController implements HandlesAjaxModelContract
{
    use HandlesAjaxModel;

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return MenuItem::class; 
    }

    /**
     * Adds the menu id (coming from the request path) to the store uri
     * @param  Request $request
     * @return string
     */
    protected function getStoreUri(Request $request): string
	{
		$menu = $request->route()->parameters()['menu'];
		return MenuItem::transformAjaxUri('store', [$menu], true);
	}

	/**
	 * Add the menu as a hidden field in the create form
	 * @param  Request $request
	 * @param  Form    $form
	 */
    public function afterStoreFormCreated(Request $request, Form $form)
	{
		$menu = $request->route()->parameter('menu');
		$form->setFieldValue('menu', $menu);
	}

	/**
	 * Bulk update for menu items
	 * @param  Request $request
	 * @return array
	 */
	public function patch(Request $request): array
	{
		$post = $request->post();
		if(!isset($post['models'])){
			throw new HttpException(422, "'models' must be set for a patch request");
		}
		$model = $this->getModel();
		$model = new $model;
		$models = collect();
		$this->saveItems($post['models']);
		return ['message' => 'Items have been updated'];
	}

	protected function saveItems($itemsData, $parent = null)
	{
		foreach($itemsData as $weight => $data){
			$item = MenuItem::findOrFail($data['id']);
			$item->weight = $data['weight'];
			$item->parent()->dissociate();
			if($parent){
				$item->parent()->associate($parent);
			}
			$item->save();
			if(isset($data['children'])){
				$this->saveItems($data['children'], $data['id']);
			}
		}
		\Menus::forgetItemsCache();
	}
}
