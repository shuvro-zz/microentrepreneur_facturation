@php
    $currencies = $bill->benefits->groupBy(function($b) {
        return $b->pivot->currency;
    });
@endphp
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <style>

    </style>
</head>
<body>
<table style="width: 100%">
    <tr>
        <td>
            RUARO Aurélien<br>
            170 Chemin de la Praz<br>
            73100 Saint Offenge<br>
            France<br>
            aurelien.ruaro@gmail.com<br>
            06.51.43.21.91
        </td>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td>Dispensé d'immatriculation au registre du commerce et des sociétés (RCS) et au répertoire des métiers</td>
        <td>
            {{ $bill->client->company_name }}<br>
            {{ $bill->client->siren }}<br>
            {{ $bill->client->address }}<br>
            {{ $bill->client->postal_code }} {{ $bill->client->city }}<br>
            {{ $bill->client->country }}<br>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table>
                <tr>
                    <td>Référence</td>
                    <td>{{ $bill->id }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ $bill->created_at->format('d/m/Y')}}</td>
                </tr>
                <tr>
                    <td>N° client</td>
                    <td>{{ $bill->client->id}}</td>
                </tr>
            </table>
        </td>
    </tr>
    @foreach($currencies as $currency => $benefits )
    <tr>
        <td colspan="2">
            <table style="width: 100%;">
                <caption>Payable en {{ $currency }}</caption>
                <tr>
                    <th>Quantité</th>
                    <th>Désignation</th>
                    <th>Prix unitaire HT</th>
                    <th>Prix total HT</th>
                </tr>
                @foreach($benefits as $benefit)
                    <tr>
                        <td>{{ $benefit->pivot->quantity }}</td>
                        <td>{{ $benefit->value }}</td>
                        <td>{{ $benefit->pivot->unit_price }}</td>
                        <td>{{ $benefit->pivot->unit_price *  $benefit->pivot->quantity}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 50%">&nbsp;</td>
        <td>
            <table style="width: 100%">
                <tr>
                    <td>Total HT</td>
                    <td>{{ $benefits->sum(function($b) {
                        return $b->pivot->unit_price *  $b->pivot->quantity;
                    }) }}</td>
                </tr>
            </table>
        </td>
    </tr>
    @endforeach
    <tr>
        <td style="width:50%;">&nbsp;</td>
        <td>TVA non applicable, art. 293 B du CGI</td>
    </tr>
    <tr>
        <td>En votre aimable réglement<br><br>Cordialement</td>
    </tr>
</table>
</body>
</html>
