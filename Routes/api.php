<?php

use Pingu\Menu\Entities\Menu;
use Pingu\Menu\Entities\MenuItem;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get(Menu::getApiUri('index'), ['uses' => 'ApiMenuController@index']);
Route::delete(Menu::getApiUri('delete'), ['uses' => 'ApiMenuController@destroy']);
Route::put(Menu::getApiUri('update'), ['uses' => 'ApiMenuController@update']);

Route::get(MenuItem::getApiUri('create'), ['uses' => 'ApiItemController@create']);
Route::post(MenuItem::getApiUri('store'), ['uses' => 'ApiItemController@store']);
Route::delete(MenuItem::getApiUri('delete'), ['uses' => 'ApiItemController@destroy']);
Route::get(MenuItem::getApiUri('edit'), ['uses' => 'ApiItemController@edit']);
Route::put(MenuItem::getApiUri('update'), ['uses' => 'ApiItemController@update']);
Route::patch(MenuItem::getApiUri('patch'), ['uses' => 'ApiItemController@patch']);