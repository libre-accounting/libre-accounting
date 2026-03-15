<?php

return [

    'form_description'  => 'Carrega un extracte bancari CAMT.053 (XML ISO 20022) per importar-ne les transaccions. Les línies queden preparades per a revisió abans d\'afegir res a la teva comptabilitat.',
    'account'           => 'Compte',
    'file'              => 'Fitxer d\'extracte',

    'statement'         => 'Extracte',
    'period'            => 'Període',
    'booked_at'         => 'Comptabilitzat',
    'value_date'        => 'Data valor',
    'counterparty'      => 'Contrapart',
    'remittance'        => 'Informació de la remesa',
    'reference'         => 'Referència',
    'lines'             => 'Línies',
    'imported'          => 'Importat',
    'total_lines'       => 'Total de línies',
    'imported_lines'    => 'Línies importades',

    'statuses' => [
        'pending'   => 'Pendent',
        'imported'  => 'Importat',
        'skipped'   => 'Omès',
        'duplicate' => 'Duplicat',
        'reviewing' => 'En revisió',
        'completed' => 'Completat',
    ],

    'import_selected'   => 'Importa la selecció',
    'select_all'        => 'Selecciona-ho tot',
    'set_category_all'  => 'Estableix la categoria per a tota la selecció',

    'messages' => [
        'staged'        => ':count línia(es) analitzada(es) i a punt per a revisió.',
        'committed'     => ':count transacció(ns) creada(es).',
        'truncated'     => 'L\'extracte era gran: :count línia(es) que superaven el límit no s\'han importat.',
        'iban_mismatch' => 'L\'IBAN de l\'extracte no coincideix amb el compte seleccionat. Si us plau, comprova el compte.',
    ],

    'errors' => [
        'not_xml'           => 'L\'extracte ha de ser un fitxer XML CAMT.053.',
        'unreadable'        => 'No s\'ha pogut llegir el fitxer carregat.',
        'already_imported'  => 'Aquest mateix fitxer ja s\'ha importat.',
        'staging_failed'    => 'No s\'ha pogut preparar l\'extracte per a la revisió. Torneu-ho a provar.',
        'none_selected'     => 'Selecciona almenys una línia per importar.',
        'category_required' => 'Tria una categoria per a cada línia seleccionada abans d\'importar.',
    ],

];
