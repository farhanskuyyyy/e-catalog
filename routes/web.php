<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', ['type_menu' => 'dashboard']);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix("category")
        ->name("category.")
        ->controller(CategoryController::class)
        ->group(function () {
            Route::get("/create", "create")->name("create");
            Route::get("/edit/{id}", "edit")->name("edit");
            Route::post("/store", "store")->name("store");
            Route::post("/update/{id}", "update")->name("update");
            Route::delete("/delete/{id}", "delete")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });

    Route::prefix("product")
        ->name("product.")
        ->controller(ProductController::class)
        ->group(function () {
            Route::get("/create", "create")->name("create");
            Route::get("/edit/{id}", "edit")->name("edit");
            Route::post("/store", "store")->name("store");
            Route::post("/update/{id}", "update")->name("update");
            Route::delete("/delete/{id}", "delete")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });

    Route::prefix("order")
        ->name("order.")
        ->controller(OrderController::class)
        ->group(function () {
            Route::get("/create", "create")->name("create");
            Route::get("/edit/{id}", "edit")->name("edit");
            Route::post("/store", "store")->name("store");
            Route::post("/update/{id}", "update")->name("update");
            Route::delete("/delete/{id}", "delete")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });

    Route::prefix("user")
        ->name("user.")
        ->controller(UserController::class)
        ->group(function () {
            Route::get("/create", "create")->name("create");
            Route::get("/edit/{id}", "edit")->name("edit");
            Route::post("/store", "store")->name("store");
            Route::post("/update/{id}", "update")->name("update");
            Route::delete("/delete/{id}", "delete")->name("delete");
            Route::get("/list", "getDataList")->name("list");
        });
});

require __DIR__.'/auth.php';
