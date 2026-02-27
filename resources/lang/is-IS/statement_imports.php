<?php

return [

    'form_description'  => 'Hladdu upp CAMT.053 (ISO 20022 XML) bankayfirliti til að flytja inn færslur þess. Línurnar eru settar í biðstöðu til yfirferðar áður en nokkuð er fært í bókhaldið þitt.',
    'account'           => 'Reikningur',
    'file'              => 'Yfirlitsskrá',

    'statement'         => 'Yfirlit',
    'period'            => 'Tímabil',
    'booked_at'         => 'Bókfært',
    'value_date'        => 'Gildisdagur',
    'counterparty'      => 'Mótaðili',
    'remittance'        => 'Greiðsluupplýsingar',
    'reference'         => 'Tilvísun',
    'lines'             => 'Línur',
    'imported'          => 'Flutt inn',
    'total_lines'       => 'Línur samtals',
    'imported_lines'    => 'Innfluttar línur',

    'statuses' => [
        'pending'   => 'Í bið',
        'imported'  => 'Flutt inn',
        'skipped'   => 'Sleppt',
        'duplicate' => 'Tvítekning',
        'reviewing' => 'Í yfirferð',
        'completed' => 'Lokið',
    ],

    'import_selected'   => 'Flytja inn valið',
    'select_all'        => 'Velja allt',
    'set_category_all'  => 'Setja flokk fyrir allt valið',

    'messages' => [
        'staged'        => ':count lína/línur þáttaðar og tilbúnar til yfirferðar.',
        'committed'     => ':count færsla/færslur stofnaðar.',
        'truncated'     => 'Yfirlitið var stórt: :count lína/línur umfram mörkin voru ekki fluttar inn.',
        'iban_mismatch' => 'IBAN yfirlitsins passar ekki við valda reikninginn. Vinsamlegast athugaðu reikninginn aftur.',
    ],

    'errors' => [
        'not_xml'           => 'Yfirlitið verður að vera CAMT.053 XML skrá.',
        'unreadable'        => 'Ekki tókst að lesa upphlöðnu skrána.',
        'already_imported'  => 'Þessi nákvæma skrá hefur þegar verið flutt inn.',
        'none_selected'     => 'Veldu að minnsta kosti eina línu til að flytja inn.',
        'category_required' => 'Veldu flokk fyrir hverja valda línu áður en þú flytur inn.',
    ],

];
