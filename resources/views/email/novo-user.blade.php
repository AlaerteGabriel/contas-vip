<x-mail::message>
# Boas vindas a {{ config('app.name') }}!

Prezado(a) {{$dados['us_nome']}},
Seu cadastro foi realizado com sucesso em nosso sistema de carteira!
No entanto, precisamos que você confirme este email para validar sua conta,
evitando fraudes e garantindo a integridade de suas informações.
<br><br>
Segue abaixo link para confirmar seu email e ativar sua conta:

<x-mail::button :url="$url">
    Validar Email e ativar conta
</x-mail::button>

Informações de Login:
<x-mail::panel>
Login: {{$dados['us_email']}}<br>
Senha: {{$dados['us_password']}}
</x-mail::panel>

Atenciosamente,<br>
equipe {{ config('app.name') }}
</x-mail::message>
