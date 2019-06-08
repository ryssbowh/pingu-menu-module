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


Route::get(Menu::getAjaxUri('index'), ['uses' => 'AjaxMenuController@index'])
	->middleware('can:view menus');
Route::delete(Menu::getAjaxUri('delete'), ['uses' => 'AjaxMenuController@destroy'])
	->middleware('deletableMenu')
	->middleware('can:delete menus');
Route::put(Menu::getAjaxUri('update'), ['uses' => 'AjaxMenuController@update'])
	->middleware('can:edit menus');

Route::get(MenuItem::getAjaxUri('create'), ['uses' => 'AjaxItemController@create'])
	->middleware('can:create menu items');
Route::post(MenuItem::getAjaxUri('store'), ['uses' => 'AjaxItemController@store'])
	->middleware('can:create menu items');
Route::delete(MenuItem::getAjaxUri('delete'), ['uses' => 'AjaxItemController@destroy'])
	->middleware('deletableMenuItem')
	->middleware('can:delete menu items');
Route::get(MenuItem::getAjaxUri('edit'), ['uses' => 'AjaxItemController@edit'])
	->middleware('can:edit menu items');
Route::put(MenuItem::getAjaxUri('update'), ['uses' => 'AjaxItemController@update'])
	->middleware('can:edit menu items');
Route::patch(MenuItem::getAjaxUri('patch'), ['uses' => 'AjaxItemController@patch'])
	->middleware('can:edit menu items');