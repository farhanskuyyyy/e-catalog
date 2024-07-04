<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix("category")
        ->name("category.")
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

    Route::prefix("product")
        ->name("product.")
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

    Route::prefix("order")
        ->name("order.")
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

    Route::prefix("user")
        ->name("user.")
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

require __DIR__.'/auth.php';
