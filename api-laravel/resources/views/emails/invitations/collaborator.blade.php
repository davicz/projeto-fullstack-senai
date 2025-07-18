<x-mail::message>
# Convite para se Juntar à Equipe

Olá!

Você foi convidado para se juntar à plataforma da TechnologySolutions.

Para finalizar seu cadastro e criar sua conta, por favor, clique no botão abaixo. Este link é válido por 24 horas.

<x-mail::button :url="$url">
Finalizar Cadastro
</x-mail::button>

Se você não esperava receber este convite, pode ignorar este e-mail.

Obrigado,<br>
Equipe {{ config('app.name') }}
</x-mail::message>
