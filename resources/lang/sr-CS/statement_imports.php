<?php

return [

    'form_description'  => 'Otpremite CAMT.053 (ISO 20022 XML) bankovni izvod da biste uvezli njegove transakcije. Stavke se pripremaju za pregled pre nego što se bilo šta doda u vaše knjige.',
    'account'           => 'Račun',
    'file'              => 'Datoteka izvoda',

    'statement'         => 'Izvod',
    'period'            => 'Period',
    'booked_at'         => 'Knjiženo',
    'value_date'        => 'Datum valute',
    'counterparty'      => 'Suprotna strana',
    'remittance'        => 'Podaci o uplati',
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

    'import_selected'   => 'Uvezi izabrano',
    'select_all'        => 'Izaberi sve',
    'set_category_all'  => 'Postavi kategoriju za sve izabrano',

    'messages' => [
        'staged'        => ':count stavka(i) obrađeno i spremno za pregled.',
        'committed'     => ':count transakcija(e) kreirano.',
        'truncated'     => 'Izvod je bio veliki: :count stavka(i) iznad ograničenja nije uvezeno.',
        'iban_mismatch' => 'IBAN sa izvoda ne odgovara izabranom računu. Molimo proverite račun.',
    ],

    'errors' => [
        'not_xml'           => 'Izvod mora biti CAMT.053 XML datoteka.',
        'unreadable'        => 'Otpremljena datoteka nije mogla biti pročitana.',
        'already_imported'  => 'Ova ista datoteka je već uvezena.',
        'staging_failed'    => 'Izvod nije mogao biti pripremljen za pregled. Molimo pokušajte ponovo.',
        'none_selected'     => 'Izaberite najmanje jednu stavku za uvoz.',
        'category_required' => 'Pre uvoza izaberite kategoriju za svaku izabranu stavku.',
    ],

];
