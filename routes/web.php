<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Adm_Usuarios_controller;
use App\Http\Controllers\ItemController;

Route::get('/adm_index', [Adm_Usuarios_controller::class, 'index'])->name('adm.index');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:usuario'])->group(function () {
    Route::get('/admin', [Adm_Usuarios_controller::class, 'index'])->name('admin.index');
    Route::post('/admin/create', [Adm_Usuarios_controller::class, 'create'])->name('admin.create');
    Route::get('/admin/get_user/{id}', [Adm_Usuarios_controller::class, 'get_user'])->name('admin.get_user');
    Route::post('/admin/update', [Adm_Usuarios_controller::class, 'update'])->name('admin.update');
    Route::post('/admin/delete', [Adm_Usuarios_controller::class, 'delete'])->name('admin.delete');
    Route::get('/admin/search', [Adm_Usuarios_controller::class, 'search_user'])->name('admin.search');

// En routes/web.php
Route::post('/usuario', [Adm_Usuarios_controller::class, 'create'])->name('usuario.store');

Route::get('/items', [ItemController::class, 'index']); // Leer todos los items
Route::post('/items', [ItemController::class, 'store']); // Crear un nuevo item
Route::put('/items/{id}', [ItemController::class, 'update']); // Actualizar un item
Route::delete('/items/{id}', [ItemController::class, 'destroy']); // Eliminar un item

});
