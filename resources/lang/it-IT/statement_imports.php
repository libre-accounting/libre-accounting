<?php

return [

    'form_description'  => 'Carica un estratto conto bancario CAMT.053 (ISO 20022 XML) per importarne le transazioni. Le righe vengono preparate per la revisione prima che qualsiasi cosa venga aggiunta alla tua contabilità.',
    'account'           => 'Conto',
    'file'              => 'File dell\'estratto conto',

    'statement'         => 'Estratto conto',
    'period'            => 'Periodo',
    'booked_at'         => 'Contabilizzato',
    'value_date'        => 'Data valuta',
    'counterparty'      => 'Controparte',
    'remittance'        => 'Informazioni sulla rimessa',
    'reference'         => 'Riferimento',
    'lines'             => 'Righe',
    'imported'          => 'Importate',
    'total_lines'       => 'Righe totali',
    'imported_lines'    => 'Righe importate',

    'statuses' => [
        'pending'   => 'In sospeso',
        'imported'  => 'Importata',
        'skipped'   => 'Saltata',
        'duplicate' => 'Duplicata',
        'reviewing' => 'In revisione',
        'completed' => 'Completata',
    ],

    'import_selected'   => 'Importa selezionate',
    'select_all'        => 'Seleziona tutte',
    'set_category_all'  => 'Imposta la categoria per tutte le selezionate',

    'messages' => [
        'staged'        => ':count riga/righe analizzate e pronte per la revisione.',
        'committed'     => ':count transazione/i create.',
        'truncated'     => 'L\'estratto conto era di grandi dimensioni: :count riga/righe oltre il limite non sono state importate.',
        'iban_mismatch' => 'L\'IBAN dell\'estratto conto non corrisponde al conto selezionato. Verifica nuovamente il conto.',
    ],

    'errors' => [
        'not_xml'           => 'L\'estratto conto deve essere un file XML CAMT.053.',
        'unreadable'        => 'Impossibile leggere il file caricato.',
        'already_imported'  => 'Questo stesso file è già stato importato.',
        'none_selected'     => 'Seleziona almeno una riga da importare.',
        'category_required' => 'Scegli una categoria per ogni riga selezionata prima di importare.',
    ],

];
