<?php

use Pingu\Menu\Entities\Menu;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group prefixed with admin which
| contains the "web" middleware group and the permission middleware "can:access admin area".
|
*/

Route::get(Menu::getAdminUri('index'), ['uses' => 'MenuJsGridController@index'])
	->name('menu.admin.menus')
	->middleware('can:view menus');

Route::get(Menu::getAdminUri('edit'), ['uses' => 'MenuController@edit'])
	->middleware('can:edit menus');
Route::put(Menu::getAdminUri('update'), ['uses' => 'MenuController@update'])
	->middleware('can:edit menus');

Route::get(Menu::getAdminUri('create'), ['uses' => 'MenuController@create'])
	->name('menu.admin.menus.create')
	->middleware('can:add menus');
Route::post(Menu::getAdminUri('store'), ['uses' => 'MenuController@store'])
	->middleware('can:add menus');

Route::get(Menu::getAdminUri('editItems'), ['uses' => 'MenuController@editItems'])
	->middleware('can:edit menus');
Route::put(Menu::getAdminUri('editItems'), ['uses' => 'MenuController@updateItems'])
	->middleware('can:edit menus');