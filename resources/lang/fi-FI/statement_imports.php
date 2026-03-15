<?php

return [

    'form_description'  => 'Lataa CAMT.053 (ISO 20022 XML) -tiliote tuodaksesi sen tapahtumat. Rivit asetetaan tarkistettaviksi ennen kuin mitään lisätään kirjanpitoosi.',
    'account'           => 'Tili',
    'file'              => 'Tiliotetiedosto',

    'statement'         => 'Tiliote',
    'period'            => 'Aikaväli',
    'booked_at'         => 'Kirjattu',
    'value_date'        => 'Arvopäivä',
    'counterparty'      => 'Vastapuoli',
    'remittance'        => 'Maksutiedot',
    'reference'         => 'Viite',
    'lines'             => 'Rivit',
    'imported'          => 'Tuotu',
    'total_lines'       => 'Rivejä yhteensä',
    'imported_lines'    => 'Tuotuja rivejä',

    'statuses' => [
        'pending'   => 'Odottaa',
        'imported'  => 'Tuotu',
        'skipped'   => 'Ohitettu',
        'duplicate' => 'Kaksoiskappale',
        'reviewing' => 'Tarkistetaan',
        'completed' => 'Valmis',
    ],

    'import_selected'   => 'Tuo valitut',
    'select_all'        => 'Valitse kaikki',
    'set_category_all'  => 'Aseta kategoria kaikille valituille',

    'messages' => [
        'staged'        => ':count riviä jäsennetty ja valmiina tarkistettaviksi.',
        'committed'     => ':count tapahtumaa luotu.',
        'truncated'     => 'Tiliote oli suuri: :count rajan ylittävää riviä jäi tuomatta.',
        'iban_mismatch' => 'Tiliotteen IBAN ei vastaa valittua tiliä. Tarkista tili.',
    ],

    'errors' => [
        'not_xml'           => 'Tiliotteen on oltava CAMT.053 XML -tiedosto.',
        'unreadable'        => 'Ladattua tiedostoa ei voitu lukea.',
        'already_imported'  => 'Juuri tämä tiedosto on jo tuotu.',
        'staging_failed'    => 'Tiliotetta ei voitu valmistella tarkistettavaksi. Yritä uudelleen.',
        'none_selected'     => 'Valitse vähintään yksi tuotava rivi.',
        'category_required' => 'Valitse kategoria jokaiselle valitulle riville ennen tuontia.',
    ],

];
