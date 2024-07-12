<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
        Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.auth.refresh');
        Route::post('/me', [AuthController::class, 'meUser'])->name('api.auth.me');
    });

    Route::prefix('authors')->group(function () {
        Route::get('/index', [AuthorController::class, 'index'])->name('api.authors.index');
        Route::get('/show/{author}', [AuthorController::class, 'show'])->name('api.authors.show');
        Route::post('/store', [AuthorController::class, 'store'])->name('api.authors.store');
        Route::put('/update/{author}', [AuthorController::class, 'update'])->name('api.authors.update');
        Route::delete('/destroy/{author}', [AuthorController::class, 'delete'])->name('api.authors.destroy');
    });

    Route::prefix('books')->group(function () {
        Route::get('/show', [BookController::class, 'index'])->name('api.books.index');
        Route::get('/show/{book}', [BookController::class, 'show'])->name('api.books.show');
        Route::post('/store', [BookController::class, 'store'])->name('api.books.store');
        Route::put('/update/{book}', [BookController::class, 'update'])->name('api.books.update');
        Route::delete('/destroy/{book}', [BookController::class, 'delete'])->name('api.books.destroy');
    });
});
