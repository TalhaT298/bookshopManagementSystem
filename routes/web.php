<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [BookController::class, 'index'])->name(name: 'books.index');
Route::get('books/{id}/show', [BookController::class, 'show'])->name(name: 'books.show');
Route::get('books/create', [BookController::class, 'create'])->name(name: 'books.create');
Route::post('books/', [BookController::class, 'store'])->name('books.store');
Route::get('books/search', [BookController::class, 'search'])->name('books.search');
Route::delete('books/{id}/delete', [BookController::class, 'destroy'])->name('books.destroy');

// Route::resource('books', BookController::class);