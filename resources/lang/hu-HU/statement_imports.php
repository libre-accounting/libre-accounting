<?php

return [

    'form_description'  => 'Töltsön fel egy CAMT.053 (ISO 20022 XML) bankkivonatot a tranzakciók importálásához. A sorok átnézésre várnak, mielőtt bármi bekerülne a könyvelésbe.',
    'account'           => 'Számla',
    'file'              => 'Kivonatfájl',

    'statement'         => 'Kivonat',
    'period'            => 'Időszak',
    'booked_at'         => 'Könyvelve',
    'value_date'        => 'Értéknap',
    'counterparty'      => 'Partner',
    'remittance'        => 'Közlemény',
    'reference'         => 'Hivatkozás',
    'lines'             => 'Sorok',
    'imported'          => 'Importálva',
    'total_lines'       => 'Összes sor',
    'imported_lines'    => 'Importált sorok',

    'statuses' => [
        'pending'   => 'Függőben',
        'imported'  => 'Importálva',
        'skipped'   => 'Kihagyva',
        'duplicate' => 'Duplikátum',
        'reviewing' => 'Felülvizsgálat alatt',
        'completed' => 'Befejezve',
    ],

    'import_selected'   => 'Kiválasztottak importálása',
    'select_all'        => 'Összes kijelölése',
    'set_category_all'  => 'Kategória beállítása az összes kiválasztotthoz',

    'messages' => [
        'staged'        => ':count sor feldolgozva és átnézésre kész.',
        'committed'     => ':count tranzakció létrehozva.',
        'truncated'     => 'A kivonat nagy volt: a korláton felüli :count sor nem lett importálva.',
        'iban_mismatch' => 'A kivonat IBAN-ja nem egyezik a kiválasztott számlával. Kérjük, ellenőrizze a számlát.',
    ],

    'errors' => [
        'not_xml'           => 'A kivonatnak CAMT.053 XML-fájlnak kell lennie.',
        'unreadable'        => 'A feltöltött fájl nem volt olvasható.',
        'already_imported'  => 'Ezt a fájlt pontosan ugyanígy már importálták.',
        'none_selected'     => 'Válasszon ki legalább egy sort az importáláshoz.',
        'category_required' => 'Az importálás előtt válasszon kategóriát minden kiválasztott sorhoz.',
    ],

];
