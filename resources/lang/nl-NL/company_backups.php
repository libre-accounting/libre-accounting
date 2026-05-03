<?php

return [

    'export'            => 'Exporteren / Back-up',
    'import'            => 'Back-up importeren',
    'restoring'         => 'Bezig met herstellen…',
    'switch_to_company' => 'Overschakelen naar bedrijf',

    'import_title'       => 'Herstellen vanuit een Libre Accounting-back-up',
    'import_description' => 'Upload een back-uparchief (.zip) dat vanuit Libre Accounting is geëxporteerd om het bedrijf op deze instantie opnieuw aan te maken.',
    'import_action'      => 'Back-up herstellen',

    'messages' => [
        'export_started'    => 'Back-up gestart. U ontvangt een melding zodra de download klaar is.',
        'exporting'         => 'Bezig met het opbouwen van de bedrijfsback-up…',
        'export_completed'  => 'De bedrijfsback-up is klaar.',
        'import_started'    => 'Herstel gestart. Dit kan even duren bij grote back-ups.',
        'importing'         => 'Bezig met het herstellen van het bedrijf…',
        'import_completed'  => 'Het bedrijf is succesvol hersteld.',
        'failed'            => 'Er is iets misgegaan.',
    ],

    'warnings' => [
        'title'                 => 'Het herstel is voltooid met enkele opmerkingen:',
        'disabled_modules'      => 'Deze modules worden door de back-up gebruikt maar zijn hier niet geïnstalleerd, dus ze zijn uitgeschakeld gelaten: :modules.',
        'skipped_reports'       => ':count rapport(en) zijn overgeslagen omdat het bijbehorende rapporttype niet op deze instantie is geïnstalleerd.',
        'unbundled_media'       => ':count bestand(en) waren opgeslagen op externe opslag en zijn niet meegeleverd; upload ze handmatig opnieuw.',
        'missing_currency_refs' => ':count record(s) verwezen naar een valuta die niet in de back-up voorkwam en zijn teruggevallen op de standaardvaluta.',
    ],

    'errors' => [
        'invalid_format' => 'Het geüploade bestand is geen geldige Libre Accounting-bedrijfsback-up.',
        'newer_version'  => 'Deze back-up is gemaakt door een nieuwere versie van Libre Accounting. Werk deze instantie bij voordat u herstelt.',
    ],

];
