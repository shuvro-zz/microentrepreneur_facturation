@component('mail::message')
# Cher David

Voici la facture pour {{ $bill->designation }}

@component('mail::button', ['url' => $bill->gd_web_view_link])
    {{ $bill->designation }}
@endcomponent

Merci <br>
Aurélien RUARO
@endcomponent
