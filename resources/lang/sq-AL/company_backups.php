<?php

return [

    'export'            => 'Eksporto / Kopje rezervë',
    'import'            => 'Importo kopje rezervë',
    'restoring'         => 'Po rikthehet…',
    'switch_to_company' => 'Kalo te kompania',

    'import_title'       => 'Rikthe nga një kopje rezervë e Libre Accounting',
    'import_description' => 'Ngarko një arkiv kopjeje rezervë (.zip) të eksportuar nga Libre Accounting për të rikrijuar kompaninë në këtë instancë.',
    'import_action'      => 'Rikthe kopjen rezervë',

    'messages' => [
        'export_started'    => 'Kopja rezervë filloi. Do të njoftoheni kur shkarkimi të jetë gati.',
        'exporting'         => 'Po ndërtohet kopja rezervë e kompanisë…',
        'export_completed'  => 'Kopja rezervë e kompanisë është gati.',
        'import_started'    => 'Rikthimi filloi. Kjo mund të zgjasë pak për kopje rezervë të mëdha.',
        'importing'         => 'Po rikthehet kompania…',
        'import_completed'  => 'Kompania u rikthye me sukses.',
        'failed'            => 'Diçka shkoi keq.',
    ],

    'warnings' => [
        'title'                 => 'Rikthimi përfundoi me disa shënime:',
        'disabled_modules'      => 'Këto module referencohen nga kopja rezervë, por nuk janë të instaluar këtu, ndaj u lanë të çaktivizuar: :modules.',
        'skipped_reports'       => ':count raport(e) u anashkaluan sepse lloji i tyre i raportit nuk është i instaluar në këtë instancë.',
        'unbundled_media'       => ':count skedar(ë) ishin ruajtur në ruajtje të largët dhe nuk u përfshinë; ringarkojini ato manualisht.',
        'missing_currency_refs' => ':count regjistrim(e) referuan një monedhë që nuk ishte në kopjen rezervë dhe u kthyen te monedha e parazgjedhur.',
    ],

    'errors' => [
        'invalid_format' => 'Skedari i ngarkuar nuk është një kopje rezervë e vlefshme kompanie e Libre Accounting.',
        'newer_version'  => 'Kjo kopje rezervë u krijua nga një version më i ri i Libre Accounting. Ju lutemi përditësoni këtë instancë përpara se të rikthehet.',
    ],

];
