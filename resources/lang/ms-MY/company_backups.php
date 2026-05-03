<?php

return [

    'export'            => 'Eksport / Sandaran',
    'import'            => 'Import sandaran',
    'restoring'         => 'Memulihkan…',
    'switch_to_company' => 'Tukar ke syarikat',

    'import_title'       => 'Pulihkan daripada sandaran Libre Accounting',
    'import_description' => 'Muat naik arkib sandaran (.zip) yang dieksport daripada Libre Accounting untuk mencipta semula syarikat pada instans ini.',
    'import_action'      => 'Pulihkan sandaran',

    'messages' => [
        'export_started'    => 'Sandaran dimulakan. Anda akan dimaklumkan apabila muat turun sedia.',
        'exporting'         => 'Membina sandaran syarikat…',
        'export_completed'  => 'Sandaran syarikat telah sedia.',
        'import_started'    => 'Pemulihan dimulakan. Ini mungkin mengambil sedikit masa untuk sandaran yang besar.',
        'importing'         => 'Memulihkan syarikat…',
        'import_completed'  => 'Syarikat berjaya dipulihkan.',
        'failed'            => 'Sesuatu telah berlaku.',
    ],

    'warnings' => [
        'title'                 => 'Pemulihan selesai dengan beberapa catatan:',
        'disabled_modules'      => 'Modul-modul ini dirujuk oleh sandaran tetapi tidak dipasang di sini, jadi ia dibiarkan dilumpuhkan: :modules.',
        'skipped_reports'       => ':count laporan telah dilangkau kerana jenis laporannya tidak dipasang pada instans ini.',
        'unbundled_media'       => ':count fail disimpan pada storan jauh dan tidak dibundel; muat naik semula secara manual.',
        'missing_currency_refs' => ':count rekod merujuk kepada mata wang yang tiada dalam sandaran dan berbalik kepada mata wang lalai.',
    ],

    'errors' => [
        'invalid_format' => 'Fail yang dimuat naik bukan sandaran syarikat Libre Accounting yang sah.',
        'newer_version'  => 'Sandaran ini dicipta oleh versi Libre Accounting yang lebih baharu. Sila naik taraf instans ini sebelum memulihkan.',
    ],

];
