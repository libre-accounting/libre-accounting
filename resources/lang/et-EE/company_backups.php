<?php

return [

    'export'            => 'Eksport / Varukoopia',
    'import'            => 'Impordi varukoopia',
    'restoring'         => 'Taastamine…',
    'switch_to_company' => 'Vaheta ettevõtet',

    'import_title'       => 'Taasta Libre Accounting varukoopiast',
    'import_description' => 'Laadi üles Libre Accountingust eksporditud varukoopia arhiiv (.zip), et ettevõte selles instantsis uuesti luua.',
    'import_action'      => 'Taasta varukoopia',

    'messages' => [
        'export_started'    => 'Varundamine alustatud. Sind teavitatakse, kui allalaadimine on valmis.',
        'exporting'         => 'Ettevõtte varukoopia koostamine…',
        'export_completed'  => 'Ettevõtte varukoopia on valmis.',
        'import_started'    => 'Taastamine alustatud. Suurte varukoopiate puhul võib see võtta aega.',
        'importing'         => 'Ettevõtte taastamine…',
        'import_completed'  => 'Ettevõte taastati edukalt.',
        'failed'            => 'Midagi läks valesti.',
    ],

    'warnings' => [
        'title'                 => 'Taastamine lõpetati mõnede märkustega:',
        'disabled_modules'      => 'Neile moodulitele viidatakse varukoopias, kuid need pole siin paigaldatud, seega jäeti need keelatuks: :modules.',
        'skipped_reports'       => ':count aruanne(t) jäeti vahele, sest nende aruande tüüp pole selles instantsis paigaldatud.',
        'unbundled_media'       => ':count fail(i) oli salvestatud kaugsalvestusruumi ja neid ei komplekteeritud varukoopiaga; lae need käsitsi uuesti üles.',
        'missing_currency_refs' => ':count kirje(t) viitas valuutale, mida varukoopias polnud, ja need määrati vaikevaluutale.',
    ],

    'errors' => [
        'invalid_format' => 'Üleslaaditud fail ei ole kehtiv Libre Accountingu ettevõtte varukoopia.',
        'newer_version'  => 'See varukoopia loodi Libre Accountingu uuema versiooniga. Enne taastamist uuenda palun seda instantsi.',
    ],

];
