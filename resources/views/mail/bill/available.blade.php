@component('mail::message')
# {{ $bill->client->company_name }}

Une nouvelle facture est disponible

@component('mail::button', ['url' => $bill->gd_web_view_link])
Voir la facture
@endcomponent

Toutes vos factures sont consultables <a href="{{ $bill->client->gd_web_view_link }}">ici</a>


Merci <br>
Aurélien RUARO
@endcomponent
