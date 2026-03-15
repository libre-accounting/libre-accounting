<?php

return [

    'form_description'  => 'Augšupielādējiet CAMT.053 (ISO 20022 XML) bankas izrakstu, lai importētu tā darījumus. Rindas tiek sagatavotas pārskatīšanai, pirms kaut kas tiek pievienots jūsu grāmatvedības ierakstiem.',
    'account'           => 'Konts',
    'file'              => 'Izraksta fails',

    'statement'         => 'Izraksts',
    'period'            => 'Periods',
    'booked_at'         => 'Iegrāmatots',
    'value_date'        => 'Valutēšanas datums',
    'counterparty'      => 'Darījuma partneris',
    'remittance'        => 'Maksājuma informācija',
    'reference'         => 'Atsauce',
    'lines'             => 'Rindas',
    'imported'          => 'Importēts',
    'total_lines'       => 'Rindu kopskaits',
    'imported_lines'    => 'Importētās rindas',

    'statuses' => [
        'pending'   => 'Gaida',
        'imported'  => 'Importēts',
        'skipped'   => 'Izlaists',
        'duplicate' => 'Dublikāts',
        'reviewing' => 'Pārskatīšanā',
        'completed' => 'Pabeigts',
    ],

    'import_selected'   => 'Importēt atlasītās',
    'select_all'        => 'Atlasīt visas',
    'set_category_all'  => 'Iestatīt kategoriju visām atlasītajām',

    'messages' => [
        'staged'        => 'Nolasīta(s) un pārskatīšanai gatava(s) :count rinda(s).',
        'committed'     => 'Izveidots(i) :count darījums(i).',
        'truncated'     => 'Izraksts bija liels: :count rinda(s), kas pārsniedza ierobežojumu, netika importēta(s).',
        'iban_mismatch' => 'Izraksta IBAN neatbilst atlasītajam kontam. Lūdzu, vēlreiz pārbaudiet kontu.',
    ],

    'errors' => [
        'not_xml'           => 'Izrakstam jābūt CAMT.053 XML failam.',
        'unreadable'        => 'Augšupielādēto failu nevarēja nolasīt.',
        'already_imported'  => 'Tieši šis fails jau ir importēts.',
        'staging_failed'    => 'Neizdevās sagatavot izrakstu pārskatīšanai. Lūdzu, mēģiniet vēlreiz.',
        'none_selected'     => 'Atlasiet vismaz vienu rindu importēšanai.',
        'category_required' => 'Pirms importēšanas izvēlieties kategoriju katrai atlasītajai rindai.',
    ],

];
