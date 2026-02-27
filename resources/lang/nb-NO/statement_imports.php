<?php

return [

    'form_description'  => 'Last opp en CAMT.053-kontoutskrift (ISO 20022 XML) for å importere transaksjonene. Linjene klargjøres for gjennomgang før noe legges til i regnskapet.',
    'account'           => 'Konto',
    'file'              => 'Kontoutskriftsfil',

    'statement'         => 'Kontoutskrift',
    'period'            => 'Periode',
    'booked_at'         => 'Bokført',
    'value_date'        => 'Valuteringsdato',
    'counterparty'      => 'Motpart',
    'remittance'        => 'Betalingsinformasjon',
    'reference'         => 'Referanse',
    'lines'             => 'Linjer',
    'imported'          => 'Importert',
    'total_lines'       => 'Totalt antall linjer',
    'imported_lines'    => 'Importerte linjer',

    'statuses' => [
        'pending'   => 'Venter',
        'imported'  => 'Importert',
        'skipped'   => 'Hoppet over',
        'duplicate' => 'Duplikat',
        'reviewing' => 'Under gjennomgang',
        'completed' => 'Fullført',
    ],

    'import_selected'   => 'Importer valgte',
    'select_all'        => 'Velg alle',
    'set_category_all'  => 'Angi kategori for alle valgte',

    'messages' => [
        'staged'        => ':count linje(r) tolket og klar for gjennomgang.',
        'committed'     => ':count transaksjon(er) opprettet.',
        'truncated'     => 'Kontoutskriften var stor: :count linje(r) utover grensen ble ikke importert.',
        'iban_mismatch' => 'IBAN i kontoutskriften samsvarer ikke med den valgte kontoen. Kontroller kontoen på nytt.',
    ],

    'errors' => [
        'not_xml'           => 'Kontoutskriften må være en CAMT.053 XML-fil.',
        'unreadable'        => 'Den opplastede filen kunne ikke leses.',
        'already_imported'  => 'Akkurat denne filen er allerede importert.',
        'none_selected'     => 'Velg minst én linje å importere.',
        'category_required' => 'Velg en kategori for hver valgte linje før import.',
    ],

];
