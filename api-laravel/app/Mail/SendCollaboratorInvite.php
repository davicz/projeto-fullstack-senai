<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCollaboratorInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * O token do convite.
     * Declarar como 'public' a torna automaticamente disponível na sua view/template.
     * @var string
     */
    public string $token;

    /**
     * Cria uma nova instância da mensagem.
     *
     * @param string $token O token do convite vindo do InvitationService.
     */
    public function __construct(string $token)
    {
        // 1. A CORREÇÃO ESTÁ AQUI:
        // Armazenamos o token recebido na propriedade pública da classe.
        $this->token = $token;
    }

    /**
     * Define o "envelope" da mensagem (assunto, etc.).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Você foi convidado para se juntar à TechnologySolutions!',
        );
    }

    /**
     * Define o conteúdo da mensagem.
     */
    public function content(): Content
    {
        // 2. AGORA O LARAVEL PODE CONSTRUIR A URL CORRETAMENTE:
        // Ele usa a propriedade pública '$this->token' que definimos no construtor.
        return new Content(
            markdown: 'emails.invitations.collaborator',
            with: [
                'url' => config('app.frontend_url', 'http://localhost:4200') . '/finalizar-cadastro?token=' . $this->token,
            ],
        );
    }

    /**
     * Define os anexos da mensagem.
     */
    public function attachments(): array
    {
        return [];
    }
}
