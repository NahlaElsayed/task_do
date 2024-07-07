<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ParentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\UserRegiserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserRegiserController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forget-password-email', 'forgetEmail');
    Route::post('verify-otp-email','verifyOTP');
});
Route::controller(ParentController::class)->group(function(){
    Route::post('parent', 'add');
    Route::get('all-parents', 'all');
    
 
});

Route::controller(CategoryController::class)->group(function(){
    Route::post('category', 'add');
    Route::get('all-categories', 'all');
    Route::post('update-category/{id}', 'updateCategory');
    Route::delete('detete-category/{id}', 'destroy');

 
});
Route::controller(ProductController::class)->group(function(){
    Route::post('product', 'add');
    Route::get('all-products', 'all');
    Route::post('update-product/{id}', 'updateProduct');
    Route::delete('detete-product/{id}', 'destroy');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('update-password', [UserRegiserController::class, 'updatePassword']);
    Route::post('update-profile', [UserRegiserController::class, 'updateProfile']);
    Route::get('user-date', [UserRegiserController::class, 'getUserDate']);

    
});
