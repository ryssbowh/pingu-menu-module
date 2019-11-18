<?php

use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

/*
|--------------------------------------------------------------------------
| Ajax Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register ajax web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group prefixed with ajax which
| contains the "ajax" middleware group.
|
*/

/**
 * Menus
 */
// Route::get(Menu::getUri('index'), ['uses' => 'MenuJsGridController@jsGridIndex'])
// 	->middleware('can:view menus');
// Route::delete(Menu::getUri('delete'), ['uses' => 'AjaxMenuController@delete'])
// 	->middleware('deletableModel:'.Menu::routeSlug())
// 	->middleware('can:delete menus');
// Route::put(Menu::getUri('update'), ['uses' => 'AjaxMenuController@update'])
// 	->middleware('can:edit menus');

/**
 * Items
 */
// Route::get(MenuItem::getUri('create'), ['uses' => 'AjaxItemController@create'])
// 	->middleware('can:create menu items');
// Route::post(MenuItem::getUri('store'), ['uses' => 'AjaxItemController@store'])
// 	->middleware('can:create menu items');
// Route::delete(MenuItem::getUri('delete'), ['uses' => 'AjaxItemController@delete'])
// 	->middleware('deletableModel:'.MenuItem::routeSlug())
// 	->middleware('can:delete menu items');
// Route::get(MenuItem::getUri('edit'), ['uses' => 'AjaxItemController@edit'])
// 	->middleware('can:edit menu items');
// Route::put(MenuItem::getUri('update'), ['uses' => 'AjaxItemController@update'])
// 	->middleware('can:edit menu items');
// Route::patch(MenuItem::getUri('patch'), ['uses' => 'AjaxItemController@patch'])
// 	->middleware('can:edit menu items');