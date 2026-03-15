<?php

return [

    'form_description'  => 'Nahrajte bankový výpis CAMT.053 (ISO 20022 XML) na import jeho transakcií. Riadky sa pripravia na kontrolu skôr, než sa čokoľvek pridá do vašich kníh.',
    'account'           => 'Účet',
    'file'              => 'Súbor výpisu',

    'statement'         => 'Výpis',
    'period'            => 'Obdobie',
    'booked_at'         => 'Zaúčtované',
    'value_date'        => 'Dátum valuty',
    'counterparty'      => 'Protistrana',
    'remittance'        => 'Informácie o platbe',
    'reference'         => 'Referencia',
    'lines'             => 'Riadky',
    'imported'          => 'Importované',
    'total_lines'       => 'Celkový počet riadkov',
    'imported_lines'    => 'Importované riadky',

    'statuses' => [
        'pending'   => 'Čaká sa',
        'imported'  => 'Importované',
        'skipped'   => 'Preskočené',
        'duplicate' => 'Duplicitné',
        'reviewing' => 'Kontroluje sa',
        'completed' => 'Dokončené',
    ],

    'import_selected'   => 'Importovať vybrané',
    'select_all'        => 'Vybrať všetko',
    'set_category_all'  => 'Nastaviť kategóriu pre všetky vybrané',

    'messages' => [
        'staged'        => 'Spracovaných :count riadkov, pripravených na kontrolu.',
        'committed'     => 'Vytvorených :count transakcií.',
        'truncated'     => 'Výpis bol rozsiahly: :count riadkov nad limit nebolo importovaných.',
        'iban_mismatch' => 'IBAN výpisu sa nezhoduje s vybraným účtom. Prosím, skontrolujte účet.',
    ],

    'errors' => [
        'not_xml'           => 'Výpis musí byť súbor CAMT.053 XML.',
        'unreadable'        => 'Nahraný súbor sa nepodarilo prečítať.',
        'already_imported'  => 'Presne tento súbor už bol importovaný.',
        'staging_failed'    => 'Výpis sa nepodarilo pripraviť na kontrolu. Skúste to znova.',
        'none_selected'     => 'Vyberte aspoň jeden riadok na import.',
        'category_required' => 'Pred importom vyberte kategóriu pre každý vybraný riadok.',
    ],

];
