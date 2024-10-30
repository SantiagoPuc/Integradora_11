<?php

use Illuminate\Support\Facades\Route; // Asegúrate de importar la clase Route
use App\Http\Controllers\Api\UsuariosApiController;

Route::get('/usuarios', [UsuariosApiController::class, 'index']); // Listar todos los usuarios
Route::get('/usuarios/{id}', [UsuariosApiController::class, 'getUser']); // Obtener un usuario específico
Route::post('/usuarios', [UsuariosApiController::class, 'create']); // Crear un nuevo usuario
Route::put('/usuarios/{id}', [UsuariosApiController::class, 'update']); // Actualizar un usuario
Route::delete('/usuarios/{id}', [UsuariosApiController::class, 'delete']); // Eliminar un usuario
Route::get('/usuarios/search', [UsuariosApiController::class, 'searchUser']); // Buscar usuarios
