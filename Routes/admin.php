<?php

use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

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

/**
 * Menus
 */
// Route::get(Menu::getUri('index'), ['uses' => 'MenuJsGridController@index'])
//     ->name('menu.admin.menus')
//     ->middleware('can:view menus');

// Route::get(Menu::getUri('edit'), ['uses' => 'MenuController@edit'])
//     ->middleware('can:edit menus');
// Route::put(Menu::getUri('update'), ['uses' => 'MenuController@update'])
//     ->middleware('can:edit menus');

// Route::get(Menu::getUri('create'), ['uses' => 'MenuController@create'])
//     ->name('menu.admin.menus.create')
//     ->middleware('can:add menus');
// Route::post(Menu::getUri('store'), ['uses' => 'MenuController@store'])
//     ->middleware('can:add menus');

// Route::get(Menu::getUri('editItems'), ['uses' => 'MenuController@editItems'])
//     ->middleware('can:edit menus');
// Route::put(Menu::getUri('editItems'), ['uses' => 'MenuController@updateItems'])
//     ->middleware('can:edit menus');

/**
 * Items
 */
// Route::get(MenuItem::getUri('create'), ['uses' => 'AdminItemController@create'])
//     ->middleware('can:create menu items');
// Route::post(MenuItem::getUri('store'), ['uses' => 'AdminItemController@store'])
//     ->middleware('can:create menu items');
// Route::delete(MenuItem::getUri('delete'), ['uses' => 'AdminItemController@delete'])
//     ->middleware('deletableModel:'.MenuItem::routeSlug())
//     ->middleware('can:delete menu items');
// Route::get(MenuItem::getUri('delete'), ['uses' => 'AdminItemController@confirmDelete'])
//     ->middleware('deletableModel:'.MenuItem::routeSlug())
//     ->middleware('can:delete menu items');
// Route::get(MenuItem::getUri('edit'), ['uses' => 'AdminItemController@edit'])
//     ->middleware('can:edit menu items');
// Route::put(MenuItem::getUri('update'), ['uses' => 'AdminItemController@update'])
//     ->middleware('can:edit menu items');
// Route::patch(MenuItem::getUri('patch'), ['uses' => 'AdminItemController@patch'])
//     ->middleware('can:edit menu items');