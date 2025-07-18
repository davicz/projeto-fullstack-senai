<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Importando os controllers que vamos usar
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvitationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui registramos as rotas para a nossa API.
|
*/

// ====================================================================
// --- ROTAS PÚBLICAS ---
// Rotas que qualquer pessoa pode acessar, sem precisar estar logada.
// ====================================================================

// Rota para o usuário (admin) fazer o login e obter um token
Route::post('/login', [AuthController::class, 'login']);

// Rota para o novo colaborador finalizar o cadastro a partir do link do e-mail
Route::post('/invitations/finalize', [InvitationController::class, 'finalizeRegistration']);


// ====================================================================
// --- ROTAS PROTEGIDAS ---
// Rotas que exigem um token de autenticação (Bearer Token).
// ====================================================================
Route::middleware('auth:sanctum')->group(function () {

    // Rota para um usuário autenticado (Admin/RH) criar um novo convite
    Route::post('/invitations', [InvitationController::class, 'store']);

    // Rota útil para o frontend verificar quem é o usuário logado
    Route::get('/user', function (Request $request) {
        // Retorna o usuário logado com seus perfis carregados
        return new \App\Http\Resources\UserResource($request->user()->load('roles'));
    });

});
