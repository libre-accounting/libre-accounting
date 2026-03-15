<?php

return [

    'form_description'  => 'Ladda upp ett CAMT.053-kontoutdrag (ISO 20022 XML) för att importera dess transaktioner. Raderna förbereds för granskning innan något läggs till i din bokföring.',
    'account'           => 'Konto',
    'file'              => 'Kontoutdragsfil',

    'statement'         => 'Kontoutdrag',
    'period'            => 'Period',
    'booked_at'         => 'Bokförd',
    'value_date'        => 'Valutadag',
    'counterparty'      => 'Motpart',
    'remittance'        => 'Betalningsinformation',
    'reference'         => 'Referens',
    'lines'             => 'Rader',
    'imported'          => 'Importerad',
    'total_lines'       => 'Totalt antal rader',
    'imported_lines'    => 'Importerade rader',

    'statuses' => [
        'pending'   => 'Väntar',
        'imported'  => 'Importerad',
        'skipped'   => 'Överhoppad',
        'duplicate' => 'Dubblett',
        'reviewing' => 'Granskas',
        'completed' => 'Slutförd',
    ],

    'import_selected'   => 'Importera markerade',
    'select_all'        => 'Markera alla',
    'set_category_all'  => 'Ange kategori för alla markerade',

    'messages' => [
        'staged'        => ':count rad(er) tolkade och klara för granskning.',
        'committed'     => ':count transaktion(er) skapade.',
        'truncated'     => 'Kontoutdraget var stort: :count rad(er) utöver gränsen importerades inte.',
        'iban_mismatch' => 'Kontoutdragets IBAN matchar inte det valda kontot. Kontrollera kontot igen.',
    ],

    'errors' => [
        'not_xml'           => 'Kontoutdraget måste vara en CAMT.053 XML-fil.',
        'unreadable'        => 'Den uppladdade filen kunde inte läsas.',
        'already_imported'  => 'Exakt denna fil har redan importerats.',
        'staging_failed'    => 'Kontoutdraget kunde inte förberedas för granskning. Försök igen.',
        'none_selected'     => 'Markera minst en rad att importera.',
        'category_required' => 'Välj en kategori för varje markerad rad innan import.',
    ],

];
