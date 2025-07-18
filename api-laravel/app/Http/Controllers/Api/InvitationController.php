<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinalizeRegistrationRequest;
use App\Http\Requests\SendInvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Http\Resources\UserResource;
use App\Services\InvitationService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    /**
     * Injetamos os services no construtor para que fiquem disponíveis
     * em todos os métodos do controller. Esta é a peça que faltava.
     */
    public function __construct(
        protected InvitationService $invitationService,
        protected UserService $userService
    ) {
    }

    /**
     * Armazena um novo convite.
     */
    public function store(SendInvitationRequest $request)
    {
        $invitation = $this->invitationService->createInvitation($request->validated());

        return (new InvitationResource($invitation))
                ->additional(['mensagem' => 'Convite criado com sucesso!'])
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Finaliza o cadastro de um novo colaborador a partir de um convite.
     * Este método agora funcionará corretamente.
     */
    public function finalizeRegistration(FinalizeRegistrationRequest $request)
    {
        try {
            // Esta linha agora funciona, pois '$this->userService' foi definido no construtor.
            $user = $this->userService->createUserFromInvitation($request->validated());

            return (new UserResource($user))
                    ->additional(['mensagem' => 'Cadastro finalizado com sucesso!'])
                    ->response()
                    ->setStatusCode(201);

        } catch (Exception $e) {
            // Captura qualquer erro inesperado que o Service possa lançar.
            Log::error('Falha ao finalizar cadastro: ' . $e->getMessage());

            return response()->json([
                'mensagem' => 'Ocorreu uma falha interna ao processar o seu cadastro.'
            ], 500);
        }
    }
}