<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Importando todos os controllers que vamos usar
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\CollaboratorProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui nós registramos todas as rotas para a nossa API.
|
*/

// --- ROTAS PÚBLICAS ---
// Rotas que qualquer pessoa pode acessar, sem precisar estar logada.
// --------------------------------------------------------------------

// Endpoint para convidar um novo colaborador
    Route::post('/invitations', [InvitationController::class, 'store']);

// Endpoint para o usuário fazer o login com CPF e senha
Route::post('/login', [LoginController::class, 'login']);

Route::apiResource('dogs', DogController::class);

// Endpoint para o colaborador finalizar o cadastro a partir do link do e-mail
// (A lógica será criada em um RegistrationController no futuro)
// Route::post('/register', [RegistrationController::class, 'register']);


// --- ROTAS PROTEGIDAS ---
// Todas as rotas dentro deste grupo exigem que o usuário esteja autenticado
// (ou seja, tenha feito login e esteja enviando um token válido).
// --------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Rota simples para buscar os dados do usuário que está atualmente logado.
    // O frontend usará isso para saber quem é o usuário e qual o seu perfil.
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- ROTAS COM CONTROLE DE PERFIL ---
    // Rotas que só podem ser acessadas por perfis específicos.
    // ----------------------------------------------------------------
    
    // Grupo de rotas que só podem ser acessadas por 'Administrador' ou 'Gente e Cultura'.
    // Aqui usamos o middleware 'role' que criamos!
    Route::middleware('role:Administrador,Gente e Cultura')->group(function() {
        
        // Endpoint para convidar um novo colaborador
        //Route::post('/invitations', [InvitationController::class, 'store']);

        // Endpoints para o CRUD de Perfis de Colaboradores
        // Esta única linha cria:
        // GET /collaborator-profiles (listar todos)
        // GET /collaborator-profiles/{id} (ver um)
        // PUT/PATCH /collaborator-profiles/{id} (atualizar um)
        // DELETE /collaborator-profiles/{id} (deletar um)
        Route::apiResource('collaborator-profiles', CollaboratorProfileController::class)->except(['store']);
        
        // Endpoint para listar todos os usuários (apenas como exemplo)
        Route::get('/users', [UserController::class, 'index']);
    });
});
