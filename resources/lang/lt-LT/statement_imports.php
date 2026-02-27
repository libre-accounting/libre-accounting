<?php

return [

    'form_description'  => 'Įkelkite CAMT.053 (ISO 20022 XML) banko išrašą, kad importuotumėte jo operacijas. Eilutės paruošiamos peržiūrai prieš įtraukiant ką nors į jūsų apskaitą.',
    'account'           => 'Sąskaita',
    'file'              => 'Išrašo failas',

    'statement'         => 'Išrašas',
    'period'            => 'Laikotarpis',
    'booked_at'         => 'Įtraukta į apskaitą',
    'value_date'        => 'Vertės data',
    'counterparty'      => 'Kita šalis',
    'remittance'        => 'Mokėjimo informacija',
    'reference'         => 'Nuoroda',
    'lines'             => 'Eilutės',
    'imported'          => 'Importuota',
    'total_lines'       => 'Iš viso eilučių',
    'imported_lines'    => 'Importuotos eilutės',

    'statuses' => [
        'pending'   => 'Laukiama',
        'imported'  => 'Importuota',
        'skipped'   => 'Praleista',
        'duplicate' => 'Dublikatas',
        'reviewing' => 'Peržiūrima',
        'completed' => 'Užbaigta',
    ],

    'import_selected'   => 'Importuoti pasirinktas',
    'select_all'        => 'Pasirinkti visas',
    'set_category_all'  => 'Nustatyti kategoriją visoms pasirinktoms',

    'messages' => [
        'staged'        => 'Išanalizuota :count eilutė (-ės) ir paruošta peržiūrai.',
        'committed'     => 'Sukurta :count operacija (-os).',
        'truncated'     => 'Išrašas buvo didelis: :count eilutė (-ės), viršijanti (-čios) ribą, nebuvo importuota (-os).',
        'iban_mismatch' => 'Išrašo IBAN neatitinka pasirinktos sąskaitos. Patikrinkite sąskaitą dar kartą.',
    ],

    'errors' => [
        'not_xml'           => 'Išrašas turi būti CAMT.053 XML failas.',
        'unreadable'        => 'Įkelto failo nepavyko nuskaityti.',
        'already_imported'  => 'Šis tikslus failas jau buvo importuotas.',
        'none_selected'     => 'Pasirinkite bent vieną eilutę importavimui.',
        'category_required' => 'Prieš importuodami pasirinkite kategoriją kiekvienai pasirinktai eilutei.',
    ],

];
