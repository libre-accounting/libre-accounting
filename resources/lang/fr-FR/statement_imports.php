<?php

return [

    'form_description'  => 'Importez un relevé bancaire CAMT.053 (XML ISO 20022) pour importer ses transactions. Les lignes sont préparées pour révision avant tout ajout à votre comptabilité.',
    'account'           => 'Compte',
    'file'              => 'Fichier de relevé',

    'statement'         => 'Relevé',
    'period'            => 'Période',
    'booked_at'         => 'Comptabilisé',
    'value_date'        => 'Date de valeur',
    'counterparty'      => 'Contrepartie',
    'remittance'        => 'Informations de remise',
    'reference'         => 'Référence',
    'lines'             => 'Lignes',
    'imported'          => 'Importé',
    'total_lines'       => 'Total des lignes',
    'imported_lines'    => 'Lignes importées',

    'statuses' => [
        'pending'   => 'En attente',
        'imported'  => 'Importé',
        'skipped'   => 'Ignoré',
        'duplicate' => 'Doublon',
        'reviewing' => 'En cours de révision',
        'completed' => 'Terminé',
    ],

    'import_selected'   => 'Importer la sélection',
    'select_all'        => 'Tout sélectionner',
    'set_category_all'  => 'Définir la catégorie pour toute la sélection',

    'messages' => [
        'staged'        => ':count ligne(s) analysée(s) et prête(s) pour révision.',
        'committed'     => ':count transaction(s) créée(s).',
        'truncated'     => 'Le relevé était volumineux : :count ligne(s) au-delà de la limite n\'ont pas été importées.',
        'iban_mismatch' => 'L\'IBAN du relevé ne correspond pas au compte sélectionné. Veuillez vérifier le compte.',
    ],

    'errors' => [
        'not_xml'           => 'Le relevé doit être un fichier XML CAMT.053.',
        'unreadable'        => 'Le fichier importé n\'a pas pu être lu.',
        'already_imported'  => 'Ce fichier exact a déjà été importé.',
        'staging_failed'    => 'Le relevé n\'a pas pu être préparé pour vérification. Veuillez réessayer.',
        'none_selected'     => 'Sélectionnez au moins une ligne à importer.',
        'category_required' => 'Choisissez une catégorie pour chaque ligne sélectionnée avant l\'importation.',
    ],

];
