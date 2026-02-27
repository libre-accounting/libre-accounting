<?php

return [

    'form_description'  => 'Učitajte CAMT.053 (ISO 20022 XML) bankovni izvod kako biste uvezli njegove transakcije. Stavke se pripremaju za pregled prije nego što se bilo šta doda u vaše poslovne knjige.',
    'account'           => 'Račun',
    'file'              => 'Datoteka izvoda',

    'statement'         => 'Izvod',
    'period'            => 'Razdoblje',
    'booked_at'         => 'Knjiženo',
    'value_date'        => 'Datum valute',
    'counterparty'      => 'Druga strana',
    'remittance'        => 'Podaci o doznaci',
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
        'staged'        => ':count stavka/i obrađeno i spremno za pregled.',
        'committed'     => ':count transakcija/e kreirano.',
        'truncated'     => 'Izvod je bio velik: :count stavka/i izvan ograničenja nije uvezeno.',
        'iban_mismatch' => 'IBAN sa izvoda ne odgovara odabranom računu. Molimo ponovo provjerite račun.',
    ],

    'errors' => [
        'not_xml'           => 'Izvod mora biti CAMT.053 XML datoteka.',
        'unreadable'        => 'Učitanu datoteku nije bilo moguće pročitati.',
        'already_imported'  => 'Ova tačno ista datoteka je već uvezena.',
        'none_selected'     => 'Odaberite barem jednu stavku za uvoz.',
        'category_required' => 'Prije uvoza odaberite kategoriju za svaku odabranu stavku.',
    ],

];
