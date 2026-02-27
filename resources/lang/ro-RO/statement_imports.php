<?php

return [

    'form_description'  => 'Încărcați un extras de cont bancar CAMT.053 (XML ISO 20022) pentru a importa tranzacțiile acestuia. Liniile sunt pregătite pentru verificare înainte ca ceva să fie adăugat în evidențele dvs.',
    'account'           => 'Cont',
    'file'              => 'Fișier extras de cont',

    'statement'         => 'Extras de cont',
    'period'            => 'Perioadă',
    'booked_at'         => 'Înregistrat',
    'value_date'        => 'Data valutei',
    'counterparty'      => 'Contrapartidă',
    'remittance'        => 'Informații privind plata',
    'reference'         => 'Referință',
    'lines'             => 'Linii',
    'imported'          => 'Importate',
    'total_lines'       => 'Total linii',
    'imported_lines'    => 'Linii importate',

    'statuses' => [
        'pending'   => 'În așteptare',
        'imported'  => 'Importat',
        'skipped'   => 'Omis',
        'duplicate' => 'Duplicat',
        'reviewing' => 'În verificare',
        'completed' => 'Finalizat',
    ],

    'import_selected'   => 'Importă selecția',
    'select_all'        => 'Selectează tot',
    'set_category_all'  => 'Setează categoria pentru toate selecțiile',

    'messages' => [
        'staged'        => ':count linie/linii analizate și pregătite pentru verificare.',
        'committed'     => ':count tranzacție/tranzacții create.',
        'truncated'     => 'Extrasul de cont a fost mare: :count linie/linii care depășesc limita nu au fost importate.',
        'iban_mismatch' => 'IBAN-ul din extrasul de cont nu corespunde cu contul selectat. Vă rugăm să verificați din nou contul.',
    ],

    'errors' => [
        'not_xml'           => 'Extrasul de cont trebuie să fie un fișier XML CAMT.053.',
        'unreadable'        => 'Fișierul încărcat nu a putut fi citit.',
        'already_imported'  => 'Acest fișier exact a fost deja importat.',
        'none_selected'     => 'Selectați cel puțin o linie pentru import.',
        'category_required' => 'Alegeți o categorie pentru fiecare linie selectată înainte de import.',
    ],

];
