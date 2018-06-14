<?php

use Illuminate\Http\Request;
use App\Services\VersionedRoute;

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


//Get the discounts
Route::get('{version}/discounts', VersionedRoute::getControllerClassPath('DiscountsController', 'get'));

//Discount rule management
Route::get('{version}/rules', VersionedRoute::getControllerClassPath('DiscountRulesController', 'index'));
Route::post('{version}/rules', VersionedRoute::getControllerClassPath('DiscountRulesController', 'store'));
Route::put('{version}/rules/{id}', VersionedRoute::getControllerClassPath('DiscountRulesController', 'update'));
Route::delete('{version}/rules/{id}', VersionedRoute::getControllerClassPath('DiscountRulesController', 'destroy'));
