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
     * O token do convite que sera usado no link do e-mail.
     * @var string
     */
    public string $token;

    /**
     * Cria uma nova instância da mensagem.
     *
     * @param string $token O token do convite vindo do InvitationService.
     */
    public function __construct()
    {
        // Armazenamos o token recebido em uma propriedade publica
        // para que ele fique disponivel automaticamente no template (view).
        $this->token = $token;
    }

    /**
     * Define o "envelope" da mensagem  (assunto, remetente, etc.).
     * 
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Voce foi convidado para se juntar a TchnologySolutions!',
        );
    }

    /**
     * Define o conteudo da mensagem.
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        // Aponta para o arquivo de template Markdown que o Laravel criou para nos.
        return new Content(
            markdown: 'emails.invitations.collaborator',
            with: [
                'url' => config('app.frontend_url', 'http://localhost:4200') . '/finalizar-cadastro?token=' . $this->token,
            ],
        );
    }

    /**
     * Define os anexos da mensagem (nao usado aqui).
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
