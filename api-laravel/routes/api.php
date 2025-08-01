<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS ---
Route::post('/login', [LoginController::class, 'login']);
Route::post('/invitations/finalize', [InvitationController::class, 'finalizeRegistration']);


// --- ROTAS PROTEGIDAS ---
Route::middleware('auth:sanctum')->group(function () {

    // Rota para obter os dados do utilizador autenticado
    Route::get('/user', function (Request $request) {
        return new \App\Http\Resources\UserResource($request->user()->load('roles'));
    });

    // Rota para criar um novo convite
    Route::post('/invitations', [InvitationController::class, 'store']);

    // --- ROTAS DE GESTÃO DE UTILIZADORES ---
    // A ordem aqui é CRÍTICA. As rotas mais específicas devem vir PRIMEIRO.

    // 1. Rota para EXPORTAR. É específica e deve vir antes da rota de 'show'.
    Route::get('/users/export', [UserController::class, 'export'])->name('api.users.export');

    // 2. Rota para LISTAR todos os utilizadores.
    Route::get('/users', [UserController::class, 'index'])->name('api.users.index');

    // 3. Rota para MOSTRAR um utilizador específico. Como usa um parâmetro, vem depois das rotas estáticas.
    Route::get('/users/{user}', [UserController::class, 'show'])->name('api.users.show');

    // 4. Rota para ATUALIZAR O PERFIL de um utilizador.
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('api.users.updateRole');
});
