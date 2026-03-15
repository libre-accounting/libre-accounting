<?php

return [

    'form_description'  => 'Отпремите CAMT.053 (ISO 20022 XML) банковни извод да бисте увезли његове трансакције. Ставке се припремају за преглед пре него што се било шта дода у ваше књиге.',
    'account'           => 'Рачун',
    'file'              => 'Датотека извода',

    'statement'         => 'Извод',
    'period'            => 'Период',
    'booked_at'         => 'Прокњижено',
    'value_date'        => 'Датум валуте',
    'counterparty'      => 'Друга страна',
    'remittance'        => 'Подаци о уплати',
    'reference'         => 'Референца',
    'lines'             => 'Ставке',
    'imported'          => 'Увезено',
    'total_lines'       => 'Укупно ставки',
    'imported_lines'    => 'Увезене ставке',

    'statuses' => [
        'pending'   => 'На чекању',
        'imported'  => 'Увезено',
        'skipped'   => 'Прескочено',
        'duplicate' => 'Дупликат',
        'reviewing' => 'У прегледу',
        'completed' => 'Завршено',
    ],

    'import_selected'   => 'Увези изабрано',
    'select_all'        => 'Изабери све',
    'set_category_all'  => 'Постави категорију за све изабрано',

    'messages' => [
        'staged'        => 'Обрађено :count ставки и спремно за преглед.',
        'committed'     => 'Креирано :count трансакција.',
        'truncated'     => 'Извод је био велик: :count ставки изнад ограничења није увезено.',
        'iban_mismatch' => 'IBAN са извода се не подудара са изабраним рачуном. Молимо вас да поново проверите рачун.',
    ],

    'errors' => [
        'not_xml'           => 'Извод мора бити CAMT.053 XML датотека.',
        'unreadable'        => 'Отпремљена датотека није могла бити прочитана.',
        'already_imported'  => 'Ова иста датотека је већ увезена.',
        'staging_failed'    => 'Izvod nije mogao biti pripremljen za pregled. Molimo pokušajte ponovo.',
        'none_selected'     => 'Изаберите најмање једну ставку за увоз.',
        'category_required' => 'Изаберите категорију за сваку изабрану ставку пре увоза.',
    ],

];
