<?php

use App\Http\Controllers\ClientSide\ShopCartController;
use App\Http\Controllers\ClientSide\ShopOrderController;
use App\Http\Controllers\ClientSide\ShopProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientSide\ShopProfileController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// ============ API Routes for AJAX (Online Shop) ============
Route::prefix('shop')->name('api.shop.')->group(function () {
    // Public API endpoints
    Route::get('/products/featured', [ShopProductController::class, 'getFeaturedProducts']);
    Route::get('/products/on-sale', [ShopProductController::class, 'getOnSaleProducts']);
    Route::get('/cart/count', [ShopCartController::class, 'getCartCount']);
    Route::get('/cart/total', [ShopCartController::class, 'getCartTotal']);
    
    // Authenticated API endpoints
    Route::middleware('auth:customer')->group(function () {
        Route::get('/orders/latest', [ShopOrderController::class, 'getLatestOrders']);
        Route::get('/user/info', [ShopProfileController::class, 'getUserInfo']);
    });
});
