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

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('merchants', MerchantController::class);

    Route::prefix("categories")
        ->name("categories.")
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
            Route::get("/create", "create")->name("create");
            Route::get("/{id}/edit", "edit")->name("edit");
            Route::get("/{id}/show", "show")->name("show");
            Route::post("/store", "store")->name("store");
            Route::post("/{id}/update", "update")->name("update");
            Route::delete("/{id}/delete", "destroy")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });

    Route::prefix("products")
        ->name("products.")
        ->controller(ProductController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
            Route::get("/create", "create")->name("create");
            Route::get("/{id}/edit", "edit")->name("edit");
            Route::get("/{id}/show", "show")->name("show");
            Route::post("/store", "store")->name("store");
            Route::post("/{id}/update", "update")->name("update");
            Route::delete("/{id}/delete", "destroy")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });

    Route::prefix("orders")
        ->name("orders.")
        ->controller(OrderController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
            Route::get("/create", "create")->name("create");
            Route::get("/{id}/edit", "edit")->name("edit");
            Route::get("/{id}/show", "show")->name("show");
            Route::post("/store", "store")->name("store");
            Route::post("/{id}/update", "update")->name("update");
            Route::delete("/{id}/delete", "destroy")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });

    Route::prefix("users")
        ->name("users.")
        ->controller(UserController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
            Route::get("/create", "create")->name("create");
            Route::get("/{id}/edit", "edit")->name("edit");
            Route::get("/{id}/show", "show")->name("show");
            Route::post("/store", "store")->name("store");
            Route::post("/{id}/update", "update")->name("update");
            Route::delete("/{id}/delete", "destroy")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });
});

require __DIR__ . '/auth.php';
