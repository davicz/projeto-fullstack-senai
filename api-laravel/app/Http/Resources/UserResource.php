<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transforma o recurso em um array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Aqui definimos a "cara" do nosso objeto de usuário na API.
        return [
            // --- DADOS DE IDENTIFICAÇÃO ---
            'id' => $this->id,
            'nome_completo' => $this->name,
            'email' => $this->email,
            'cpf_formatado' => $this->cpf, // O Accessor no Model já formata para 'xxx.xxx.xxx-xx'

            // --- DADOS DE CONTATO E ENDEREÇO (se existirem) ---
            'telefone' => $this->phone,
            'cep' => $this->cep,
            'endereco' => [
                'logradouro' => $this->street,
                'bairro' => $this->neighborhood,
                'cidade' => $this->locality,
                'uf' => $this->uf,
            ],

            // --- DADOS DE RELACIONAMENTO ---
            // Aqui usamos o RoleResource que criamos para formatar cada perfil.
            // 'whenLoaded' é uma boa prática: só inclui os perfis se eles
            // foram carregados previamente (evita consultas extras ao banco).
            'perfis' => RoleResource::collection($this->whenLoaded('roles')),

            // --- DADOS DE METADADOS (Transformação) ---
            'data_criacao' => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
