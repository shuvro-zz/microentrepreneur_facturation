@php
    $currencies = $bill->benefits->groupBy(function($b) {
        return $b->pivot->currency;
    });
@endphp
<div class="pdf-bill">
    <table style="width: 100%">
        <tr>
            <td style="width: 50%">
                RUARO Aurélien<br>
                170 Chemin de la Praz<br>
                73100 Saint Offenge<br>
                France<br>
                aurelien.ruaro@gmail.com<br>
                + 33 (0) 6.51.43.21.91
            </td>
            <td style="vertical-align: top; text-align: right">
                Facture n°{{ $bill->id }}
            </td>
        </tr>
        <tr>
            <td><p style="font-size: 11px">Dispensé d'immatriculation au registre du commerce et des sociétés (RCS) et
                    au répertoire des métiers</p>
            </td>
            <td>
                <div style="float: right">
                    {{ $bill->client->company_name }}<br>
                    {{ $bill->client->address }}<br>
                    {{ $bill->client->postal_code }} {{ $bill->client->city }}<br>
                    {{ $bill->client->country }}<br>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%;margin-top: 75px">
        <tr>
            <td colspan="2">
                <table style="margin-bottom: 30px">
                    <tr>
                        <td style="width:  100px">Référence :</td>
                        <td>{{ $bill->id }}</td>
                    </tr>
                    <tr>
                        <td>Date :</td>
                        <td>{{ $bill->created_at->format('d/m/Y')}}</td>
                    </tr>
                    <tr>
                        <td>N° client :</td>
                        <td>{{ $bill->client->id}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        @foreach($currencies as $currency => $benefits )
            <tr>
                <td colspan="2">
                    <h3 style=" font-weight: bold; text-align: left; padding: 0; margin: 0">{{$bill->designation}}</h3>
                    <table style="width: 100%; margin-top: 10px" class="benefit" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <th>Désignation</th>
                            <th>Quantité</th>
                            <th>Prix unitaire HT</th>
                            <th>Prix total HT</th>
                        </tr>
                        @foreach($benefits as $benefit)
                            <tr>
                                <td>{{ $benefit->value }}</td>
                                <td>{{ $benefit->pivot->quantity }}</td>
                                <td>{{ $benefit->pivot->unit_price }}</td>
                                <td>{{ $benefit->pivot->unit_price *  $benefit->pivot->quantity}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="tot">
                            <td colspan="2" class="border-0">&nbsp;</td>
                            <td>Total HT</td>
                            <td>{{ $benefits->sum(function($b) {
                        return $b->pivot->unit_price *  $b->pivot->quantity;
                    }) }} {{ $currency }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border-0"></td>
                            <td colspan="2" class="border-0">
                                <small>TVA non applicable, art. 293 B du CGI</small>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        @endforeach
        <tr>
            <td style="width:50%;">&nbsp;</td>

        </tr>
        <tr>
            <td>En votre aimable réglement<br><br>Cordialement</td>
        </tr>
    </table>
    <br>
    <br>
    @foreach($currencies as $currency => $benefits )

        <p>
            Paiement par virement (en {{ $currency }}) :
        </p>
        @if($currency == 'CHF')
            <p>Banque : Crédit Agricole next bank (Suisse) SA</p>
            <p>
                IBAN : CH21 0824 3068 2467 0000 1
            </p>
            <p>
                BIC. BANK IDENTIFIER CODE : AGRICHGXXXX
            </p>
        @endif
        @if($currency == '€')
            <p>Banque : CA DES SAVOIE</p>
            <p>
                IBAN :  FR76 1810 6000 1596 7553 0680 862
            </p>
            <p>
                BIC. BANK IDENTIFIER CODE : AGRIFRPP881
            </p>
        @endif
    @endforeach

</div>
