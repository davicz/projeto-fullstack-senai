<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendInvitationRequest;   // Usa a Request
use App\Http\Resources\InvitationResource;   // Usa o Resource
use App\Services\InvitationService;            // Usa o Service

class InvitationController extends Controller
{
    // Injetamos o Service no construtor para que o Laravel o forneça automaticamente.
    public function __construct(protected InvitationService $invitationService)
    {
    }

    /**
     * Armazena um novo convite no banco de dados.
     *
     * @param  \App\Http\Requests\SendInvitationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SendInvitationRequest $request)
    {
        // 1. A validação já foi feita automaticamente pela SendInvitationRequest.
        //    O controller recebe apenas dados validados e seguros.
        $validatedData = $request->validated();

        // 2. O controller DELEGA a lógica de negócio para o Service.
        //    Sua única responsabilidade é chamar o método correto.
        $invitation = $this->invitationService->createInvitation($validatedData);

        // 3. O controller DELEGA a formatação da resposta para o Resource.
        //    Isso garante uma resposta consistente e segura.
        return (new InvitationResource($invitation))
                ->additional(['mensagem' => 'Convite criado com sucesso!'])
                ->response()
                ->setStatusCode(201); // Retorna o status 201 Created
    }

    // ... aqui você adicionará os outros métodos, como finalizeRegistration, etc.
}
