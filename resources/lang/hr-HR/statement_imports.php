<?php

return [

    'form_description'  => 'Učitajte bankovni izvod CAMT.053 (ISO 20022 XML) za uvoz njegovih transakcija. Stavke se pripremaju za pregled prije nego što se bilo što doda u vaše knjige.',
    'account'           => 'Račun',
    'file'              => 'Datoteka izvoda',

    'statement'         => 'Izvod',
    'period'            => 'Razdoblje',
    'booked_at'         => 'Proknjiženo',
    'value_date'        => 'Datum valute',
    'counterparty'      => 'Druga strana',
    'remittance'        => 'Podaci o plaćanju',
    'reference'         => 'Referenca',
    'lines'             => 'Stavke',
    'imported'          => 'Uvezeno',
    'total_lines'       => 'Ukupno stavki',
    'imported_lines'    => 'Uvezene stavke',

    'statuses' => [
        'pending'   => 'Na čekanju',
        'imported'  => 'Uvezeno',
        'skipped'   => 'Preskočeno',
        'duplicate' => 'Duplikat',
        'reviewing' => 'U pregledu',
        'completed' => 'Završeno',
    ],

    'import_selected'   => 'Uvezi odabrano',
    'select_all'        => 'Označi sve',
    'set_category_all'  => 'Postavi kategoriju za sve odabrane',

    'messages' => [
        'staged'        => 'Obrađeno :count stavki, spremno za pregled.',
        'committed'     => 'Kreirano :count transakcija.',
        'truncated'     => 'Izvod je bio velik: :count stavki iznad ograničenja nije uvezeno.',
        'iban_mismatch' => 'IBAN izvoda ne odgovara odabranom računu. Molimo provjerite račun.',
    ],

    'errors' => [
        'not_xml'           => 'Izvod mora biti CAMT.053 XML datoteka.',
        'unreadable'        => 'Učitanu datoteku nije moguće pročitati.',
        'already_imported'  => 'Ova točna datoteka već je uvezena.',
        'none_selected'     => 'Odaberite barem jednu stavku za uvoz.',
        'category_required' => 'Prije uvoza odaberite kategoriju za svaku odabranu stavku.',
    ],

];
