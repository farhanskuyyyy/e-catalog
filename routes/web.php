<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;

Route::get('/', [PortalController::class, 'index'])->name('portal');
Route::post('/create-order', [PortalController::class, 'createOrder'])->name('create-order');
Route::get('/check-order', [PortalController::class, 'checkOrder'])->name('check-order');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("roles/list", [RoleController::class, "getDataList"])->name("roles.list");
    Route::resource('roles', RoleController::class);

    Route::get("permissions/list", [PermissionController::class, "getDataList"])->name("permissions.list");
    Route::resource('permissions', PermissionController::class);

    Route::get("merchants/list", [MerchantController::class, "getDataList"])->name("merchants.list");
    Route::resource('merchants', MerchantController::class);

    Route::get("categories/list", [CategoryController::class, "getDataList"])->name("categories.list");
    Route::resource('categories', CategoryController::class);

    Route::get("products/list", [ProductController::class, "getDataList"])->name("products.list");
    Route::resource('products', ProductController::class);

    Route::get("orders/list", [OrderController::class, "getDataList"])->name("orders.list");
    Route::resource('orders', OrderController::class);

    Route::get("users/list", [UserController::class, "getDataList"])->name("users.list");
    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
