<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\CatalogController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/authenticate', [UserAuthController::class, 'authenticate']);
    Route::get('/signup', [UserAuthController::class, 'signup']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'catalog'

], function ($router) {
    Route::get('/', [CatalogController::class, 'show']);
});



