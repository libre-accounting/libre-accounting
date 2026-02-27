<?php

return [

    'form_description'  => 'Naložite bančni izpisek CAMT.053 (ISO 20022 XML) za uvoz transakcij. Vrstice se pripravijo za pregled, preden se karkoli doda v vaše knjige.',
    'account'           => 'Račun',
    'file'              => 'Datoteka izpiska',

    'statement'         => 'Izpisek',
    'period'            => 'Obdobje',
    'booked_at'         => 'Knjiženo',
    'value_date'        => 'Datum valute',
    'counterparty'      => 'Nasprotna stranka',
    'remittance'        => 'Podatki o plačilu',
    'reference'         => 'Sklic',
    'lines'             => 'Vrstice',
    'imported'          => 'Uvoženo',
    'total_lines'       => 'Skupaj vrstic',
    'imported_lines'    => 'Uvožene vrstice',

    'statuses' => [
        'pending'   => 'V čakanju',
        'imported'  => 'Uvoženo',
        'skipped'   => 'Preskočeno',
        'duplicate' => 'Podvojeno',
        'reviewing' => 'V pregledu',
        'completed' => 'Zaključeno',
    ],

    'import_selected'   => 'Uvozi izbrano',
    'select_all'        => 'Izberi vse',
    'set_category_all'  => 'Nastavi kategorijo za vse izbrane',

    'messages' => [
        'staged'        => 'Razčlenjenih in pripravljenih za pregled je :count vrstic.',
        'committed'     => 'Ustvarjenih je :count transakcij.',
        'truncated'     => 'Izpisek je bil obsežen: :count vrstic nad omejitvijo ni bilo uvoženih.',
        'iban_mismatch' => 'IBAN izpiska se ne ujema z izbranim računom. Prosimo, ponovno preverite račun.',
    ],

    'errors' => [
        'not_xml'           => 'Izpisek mora biti datoteka CAMT.053 XML.',
        'unreadable'        => 'Naložene datoteke ni bilo mogoče prebrati.',
        'already_imported'  => 'Prav ta datoteka je bila že uvožena.',
        'none_selected'     => 'Za uvoz izberite vsaj eno vrstico.',
        'category_required' => 'Pred uvozom izberite kategorijo za vsako izbrano vrstico.',
    ],

];
