<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvitationResource extends JsonResource
{
    /**
     * Transforma o recurso em um array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Aqui definimos exatamente como o JSON de resposta deve se parecer.
        // É uma camada de proteção para não expor dados sensíveis.
        return [
            'id' => $this->id,
            'email_convidado' => $this->email,
            'status' => $this->status,
            // 'token' => $this->token, // Geralmente não é bom expor o token na resposta
            'expira_em' => $this->expires_at->format('d/m/Y H:i:s'),
            'criado_em' => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
