<?php

return [

    'form_description'  => 'Upload et CAMT.053-kontoudtog (ISO 20022 XML) for at importere dets transaktioner. Linjerne klargøres til gennemgang, før noget tilføjes til dit regnskab.',
    'account'           => 'Konto',
    'file'              => 'Kontoudtogsfil',

    'statement'         => 'Kontoudtog',
    'period'            => 'Periode',
    'booked_at'         => 'Bogført',
    'value_date'        => 'Valørdato',
    'counterparty'      => 'Modpart',
    'remittance'        => 'Posteringstekst',
    'reference'         => 'Reference',
    'lines'             => 'Linjer',
    'imported'          => 'Importeret',
    'total_lines'       => 'Linjer i alt',
    'imported_lines'    => 'Importerede linjer',

    'statuses' => [
        'pending'   => 'Afventer',
        'imported'  => 'Importeret',
        'skipped'   => 'Sprunget over',
        'duplicate' => 'Dublet',
        'reviewing' => 'Under gennemgang',
        'completed' => 'Fuldført',
    ],

    'import_selected'   => 'Importér valgte',
    'select_all'        => 'Vælg alle',
    'set_category_all'  => 'Angiv kategori for alle valgte',

    'messages' => [
        'staged'        => ':count linje(r) indlæst og klar til gennemgang.',
        'committed'     => ':count transaktion(er) oprettet.',
        'truncated'     => 'Kontoudtoget var stort: :count linje(r) ud over grænsen blev ikke importeret.',
        'iban_mismatch' => 'Kontoudtogets IBAN stemmer ikke overens med den valgte konto. Kontrollér venligst kontoen.',
    ],

    'errors' => [
        'not_xml'           => 'Kontoudtoget skal være en CAMT.053 XML-fil.',
        'unreadable'        => 'Den uploadede fil kunne ikke læses.',
        'already_imported'  => 'Netop denne fil er allerede blevet importeret.',
        'none_selected'     => 'Vælg mindst én linje at importere.',
        'category_required' => 'Vælg en kategori for hver valgt linje, før du importerer.',
    ],

];
