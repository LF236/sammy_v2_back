
@component('mail::message')
# Hola

Haz clic en el siguiente enlace para validar tu cuenta:

@component('mail::button', ['url' => $url])
Acceder a mi cuenta
@endcomponent

Este enlace expirará pronto.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
