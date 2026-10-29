<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::prefix('posts')->controller(PostController::class)->group(function () {
    Route::get('/', 'index')->name('posts.index');
    Route::get('/create', 'create')->name('posts.create');
    Route::post('/', 'store')->name('posts.store');
    Route::get('/{post}', 'show')->name('posts.show');
    Route::get('/{post}/edit', 'edit')->name('posts.edit');
    Route::put('/{post}', 'update')->name('posts.update');
    Route::delete('/{post}', 'destroy')->name('posts.destroy');
});

/* // Ruta para mostrar el listado de registros
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

// Ruta para mostrar el formulario de creación de un nuevo registro
Route::get('/posts/create', [PostController::class, 'create'])
    ->name('posts.create');

// Ruta para almacenar un nuevo registro
Route::post('/posts', [PostController::class, 'store'])
    ->name('posts.store');

// Ruta para mostrar un registro específico
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show');

// Ruta para mostrar el formulario de edición de un registro existente
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
    ->name('posts.edit');

// Ruta para actualizar un registro existente
Route::put('/posts/{post}', [PostController::class, 'update'])
    ->name('posts.update');

// Ruta para eliminar un registro existente
Route::delete('/posts/{post}', [PostController::class, 'destroy'])
    ->name('posts.destroy'); */

// Route::resource('posts', PostController::class);