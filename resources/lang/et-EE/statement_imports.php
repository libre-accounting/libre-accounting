<?php

return [

    'form_description'  => 'Laadige üles CAMT.053 (ISO 20022 XML) pangaväljavõte, et importida selle tehingud. Read pannakse ülevaatamiseks ootele, enne kui midagi teie raamatupidamisse lisatakse.',
    'account'           => 'Konto',
    'file'              => 'Väljavõtte fail',

    'statement'         => 'Väljavõte',
    'period'            => 'Periood',
    'booked_at'         => 'Kirjendatud',
    'value_date'        => 'Väärtuspäev',
    'counterparty'      => 'Vastaspool',
    'remittance'        => 'Makseteave',
    'reference'         => 'Viide',
    'lines'             => 'Read',
    'imported'          => 'Imporditud',
    'total_lines'       => 'Ridu kokku',
    'imported_lines'    => 'Imporditud read',

    'statuses' => [
        'pending'   => 'Ootel',
        'imported'  => 'Imporditud',
        'skipped'   => 'Vahele jäetud',
        'duplicate' => 'Duplikaat',
        'reviewing' => 'Ülevaatamisel',
        'completed' => 'Lõpetatud',
    ],

    'import_selected'   => 'Impordi valitud',
    'select_all'        => 'Vali kõik',
    'set_category_all'  => 'Määra kategooria kõigile valitutele',

    'messages' => [
        'staged'        => ':count rida analüüsitud ja ülevaatamiseks valmis.',
        'committed'     => ':count tehingut loodud.',
        'truncated'     => 'Väljavõte oli suur: :count piiri ületavat rida jäeti importimata.',
        'iban_mismatch' => 'Väljavõtte IBAN ei ühti valitud kontoga. Palun kontrollige kontot uuesti.',
    ],

    'errors' => [
        'not_xml'           => 'Väljavõte peab olema CAMT.053 XML-fail.',
        'unreadable'        => 'Üleslaaditud faili ei õnnestunud lugeda.',
        'already_imported'  => 'See sama fail on juba imporditud.',
        'staging_failed'    => 'Väljavõtet ei õnnestunud ülevaatamiseks ette valmistada. Palun proovige uuesti.',
        'none_selected'     => 'Valige importimiseks vähemalt üks rida.',
        'category_required' => 'Valige enne importimist igale valitud reale kategooria.',
    ],

];
