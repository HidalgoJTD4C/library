<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// ---------- Books (API) ----------
Route::get('/books',           [BookController::class, 'index'])->name('api.books.index');
Route::post('/books',          [BookController::class, 'store'])->name('api.books.store');
Route::put('/books/{id}',      [BookController::class, 'update'])->name('api.books.update');
Route::delete('/books/{id}',   [BookController::class, 'destroy'])->name('api.books.destroy');

// ---------- Categories (API) ----------
Route::get('/categories',         [CategoryController::class, 'index'])->name('api.categories.index');
Route::post('/categories',        [CategoryController::class, 'store'])->name('api.categories.store');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('api.categories.destroy');
