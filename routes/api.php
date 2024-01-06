<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductOrdersController;
use App\Http\Controllers\Api\order_productController;
use App\Http\Controllers\Api\ProductReviewsController;
use App\Http\Controllers\Api\CustomerOrdersController;
use App\Http\Controllers\Api\CustomerReviewsController;
use App\Http\Controllers\Api\CategorieProductsController;
use App\Http\Controllers\Api\ProductCategoriesController;
use App\Http\Controllers\Api\categorie_productController;

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

Route::name('api.')->group(function () {
   
    Route::get('/categories', [
        CategorieController::class,
        'index',
    ])->name('categories.index');

    // Categorie Products
    Route::get('/categories/{categorie}/products', [
        CategorieProductsController::class,
        'index',
    ])->name('categories.products.index');


    Route::get('/products', [
        ProductController::class,
        'index',
    ])->name('categories.index');

    Route::get('/products/{product}', [
        ProductController::class,
        'show',
    ])->name('categories.show');
   
    // Product Reviews
    Route::get('/products/{product}/reviews', [
        ProductReviewsController::class,
        'index',
    ])->name('products.reviews.index');




  // Customer 


    Route::post('login', [CustomerController::class, 'login']);
    Route::post('register', [CustomerController::class, 'register']);
 
    Route::group(['middleware' => 'auth:api'], function(){
    Route::post('customer-details', [CustomerController::class, 'customerDetails']);
    
    //reviews
    
    Route::get('reviews', [
        CustomerReviewsController::class,
        'index',
    ])->name('customers.reviews.index');
    Route::post('reviews', [
        CustomerReviewsController::class,
        'store',
    ])->name('customers.reviews.store');
    Route::get('/reviews/{review}', [
        CustomerReviewsController::class,
        'show',
    ])->name('customers.reviews.show');
    Route::put('/reviews/{review}', [
        CustomerReviewsController::class,
        'update',
    ])->name('customers.reviews.update');
    Route::delete('/reviews/{review}', [
        CustomerReviewsController::class,
        'destroy',
    ])->name('customers.reviews.destroy');


            // Customer Orders
    Route::get('reviews', [
        CustomerReviewsController::class,
        'index',
    ])->name('customers.reviews.index');
    Route::post('reviews', [
        CustomerReviewsController::class,
        'store',
    ])->name('customers.reviews.store');
    Route::get('/reviews/{review}', [
        CustomerReviewsController::class,
        'show',
    ])->name('customers.reviews.show');
    Route::put('/reviews/{review}', [
        CustomerReviewsController::class,
        'update',
    ])->name('customers.reviews.update');
    Route::delete('/reviews/{review}', [
        CustomerReviewsController::class,
        'destroy',
    ])->name('customers.reviews.destroy');

    // Customer orders 
    
    Route::get('orders', [
        CustomerOrdersController::class,
        'index',
    ])->name('customers.orders.index');
    Route::post('stripe', [
        CustomerOrdersController::class,
        'store_stripe',
    ]);
    Route::get('/orders/{order}', [
        CustomerOrdersController::class,
        'show',
    ])->name('customers.orders.show');


   Route::post('pickup',[
    CustomerOrdersController::class,
    'store_pickup',
]);
Route::post('paypal',[
    CustomerOrdersController::class,
    'store_paypal',
]);

Route::post('charge',[
    CustomerOrdersController::class,
    'charge',
]);
Route::post('/vr', [
    CustomerOrdersController::class,
    'vr_payment',
]);
Route::post('/vr_store', [
    CustomerOrdersController::class,
    'store_vr',
]);

});





 Route::get('success', [
    CustomerOrdersController::class,
    'success',
]);
Route::get('error', [
    CustomerOrdersController::class,
    'error',
]);

    
 Route::get('vr_success', [
    CustomerOrdersController::class,
    'vr_success',
]);
    
});

    //Route::apiResource('users', UserController::class);

    /* Route::post('/products/{product}/reviews', [
        ProductReviewsController::class,
        'store',
    ])->name('products.reviews.store'); */

/*     // Product Order
    Route::get('/products/{product}/orders', [
        ProductOrdersController::class,
        'index',
    ])->name('products.orders.index');
    Route::post('/products/{product}/orders/{order}', [
        ProductOrdersController::class,
        'store',
    ])->name('products.orders.store');
    Route::delete('/products/{product}/orders/{order}', [
        ProductOrdersController::class,
        'destroy',
    ])->name('products.orders.destroy'); */

   /*  // Product Categories
    Route::get('/products/{product}/categories', [
        ProductCategoriesController::class,
        'index',
    ])->name('products.categories.index');
    Route::post('/products/{product}/categories/{categorie}', [
        ProductCategoriesController::class,
        'store',
    ])->name('products.categories.store');
    Route::delete('/products/{product}/categories/{categorie}', [
        ProductCategoriesController::class,
        'destroy',
    ])->name('products.categories.destroy');
 */
    //Route::apiResource('reviews', ReviewController::class);
    // Route::apiResource('roles', RoleController::class);
   // Route::apiResource('permissions', PermissionController::class);
/*     Route::post('/categories/{categorie}/products/{product}', [
        CategorieProductsController::class,
        'store',
    ])->name('categories.products.store');
    Route::delete('/categories/{categorie}/products/{product}', [
        CategorieProductsController::class,
        'destroy',
    ])->name('categories.products.destroy'); *///Route::apiResource('categories', CategorieController::class);// Route::apiResource('products', ProductController::class);
