<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('books.index'));

// ---------- Books (web) ----------
Route::get('/books',              [BookController::class, 'index'])->name('books.index');
Route::get('/books/create',       [BookController::class, 'create'])->name('books.create');
Route::post('/books',             [BookController::class, 'store'])->name('books.store');
Route::get('/books/{id}/edit',    [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/{id}',         [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{id}',      [BookController::class, 'destroy'])->name('books.destroy');

// ---------- Categories (web) ----------
Route::get('/categories',         [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories',        [CategoryController::class, 'store'])->name('categories.store');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
