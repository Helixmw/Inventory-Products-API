<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\api\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Public Routes
Route::post("/register", [AuthController::class,"register"]);
Route::post("/login", [AuthController::class,"login"]);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function(){
Route::get("/categories", [CategoryController::class,"index"]);
Route::post("/categories", [CategoryController::class,"add"]);
Route::get("/categories/{id}", [CategoryController::class,"getCategory"]);
Route::put("/categories/{id}", [CategoryController::class,"editCategory"]);
Route::delete("/categories/{id}", [CategoryController::class,"delete"]);
Route::get("/products", [ProductsController::class,"index"]);
Route::post("/products", [ProductsController::class,"add"]);
Route::get("/products/{id}", [ProductsController::class,"getProduct"]);
Route::put("/products/{id}", [ProductsController::class,"editProduct"]);
Route::delete("/products/{id}", [ProductsController::class,"delete"]);
Route::get("/products/category/{categoryId}", [ProductsController::class,"GetCategoryProducts"]);
Route::get("/products/brands/{brandId}", [ProductsController::class,"GetBrandProducts"]);
Route::get("/products/{categoryId}/{brandId}", [ProductsController::class,"GetCategoryAndBrandProducts"]);
Route::get("/brands", [BrandController::class,"index"]);
Route::post("/brands", [BrandController::class,"add"]);
Route::get("/brands/{id}", [BrandController::class,"getBrand"]);
Route::put("/brands/{id}", [BrandController::class,"editBrand"]);
Route::delete("/brands/{id}", [BrandController::class,"delete"]);
Route::get("/logout", [AuthController::class,"logout"]);
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
