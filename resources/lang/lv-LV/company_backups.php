<?php

return [

    'export'            => 'Eksportēt / Dublēt',
    'import'            => 'Importēt dublējumu',
    'restoring'         => 'Notiek atjaunošana…',
    'switch_to_company' => 'Pārslēgties uz uzņēmumu',

    'import_title'       => 'Atjaunot no Libre Accounting dublējuma',
    'import_description' => 'Augšupielādējiet dublējuma arhīvu (.zip), kas eksportēts no Libre Accounting, lai atjaunotu uzņēmumu šajā instancē.',
    'import_action'      => 'Atjaunot dublējumu',

    'messages' => [
        'export_started'    => 'Dublēšana ir sākta. Jūs tiksiet informēts, kad lejupielāde būs gatava.',
        'exporting'         => 'Notiek uzņēmuma dublējuma veidošana…',
        'export_completed'  => 'Uzņēmuma dublējums ir gatavs.',
        'import_started'    => 'Atjaunošana ir sākta. Lieliem dublējumiem tas var aizņemt kādu laiku.',
        'importing'         => 'Notiek uzņēmuma atjaunošana…',
        'import_completed'  => 'Uzņēmums tika veiksmīgi atjaunots.',
        'failed'            => 'Radās kļūda.',
    ],

    'warnings' => [
        'title'                 => 'Atjaunošana tika pabeigta ar dažām piezīmēm:',
        'disabled_modules'      => 'Uz šiem moduļiem ir atsauces dublējumā, taču tie šeit nav instalēti, tāpēc tie tika atstāti atspējoti: :modules.',
        'skipped_reports'       => ':count pārskats(-i) tika izlaisti, jo to pārskata veids nav instalēts šajā instancē.',
        'unbundled_media'       => ':count fails(-i) tika glabāti attālinātā krātuvē un netika iekļauti; augšupielādējiet tos atkārtoti manuāli.',
        'missing_currency_refs' => ':count ieraksts(-i) atsaucās uz valūtu, kas nebija dublējumā, un tika izmantota noklusējuma valūta.',
    ],

    'errors' => [
        'invalid_format' => 'Augšupielādētais fails nav derīgs Libre Accounting uzņēmuma dublējums.',
        'newer_version'  => 'Šis dublējums tika izveidots ar jaunāku Libre Accounting versiju. Lūdzu, pirms atjaunošanas jauniniet šo instanci.',
    ],

];
