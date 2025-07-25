<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendCollaboratorInvite; 
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvitationService
{
    /**
     * Cria um novo registro de convite no banco de dados.
     *
     * @param array $data Contém o e-mail validado.
     * @return Invitation O objeto do convite recém-criado.
     */
    public function createInvitation(array $data): Invitation
    {
        // Usamos o Model Eloquent, que é muito mais limpo e seguro
        // que o DB::table(). Ele gerencia os timestamps automaticamente.
        $invitation = Invitation::create([
            'email' => $data['email'],
            'token' => Str::random(32),
            'status' => 'Em Aberto',
            'expires_at' => Carbon::now()->addHours(24),
            'invited_by_user_id' => auth()->id(), // Pega o ID do usuário logado que está convidando
        ]);

        Mail::to($invitation->email)->send(new SendCollaboratorInvite($invitation->token));
        
        return $invitation;
    }
}
