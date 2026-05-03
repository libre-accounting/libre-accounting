<?php

return [

    'export'            => 'Ekspor / Cadangkan',
    'import'            => 'Impor cadangan',
    'restoring'         => 'Memulihkan…',
    'switch_to_company' => 'Beralih ke perusahaan',

    'import_title'       => 'Pulihkan dari cadangan Libre Accounting',
    'import_description' => 'Unggah arsip cadangan (.zip) yang diekspor dari Libre Accounting untuk membuat ulang perusahaan pada instansi ini.',
    'import_action'      => 'Pulihkan cadangan',

    'messages' => [
        'export_started'    => 'Pencadangan dimulai. Anda akan diberi tahu saat unduhan siap.',
        'exporting'         => 'Membangun cadangan perusahaan…',
        'export_completed'  => 'Cadangan perusahaan sudah siap.',
        'import_started'    => 'Pemulihan dimulai. Proses ini mungkin memakan waktu untuk cadangan berukuran besar.',
        'importing'         => 'Memulihkan perusahaan…',
        'import_completed'  => 'Perusahaan berhasil dipulihkan.',
        'failed'            => 'Terjadi kesalahan.',
    ],

    'warnings' => [
        'title'                 => 'Pemulihan selesai dengan beberapa catatan:',
        'disabled_modules'      => 'Modul-modul ini dirujuk oleh cadangan tetapi tidak terpasang di sini, sehingga tetap dinonaktifkan: :modules.',
        'skipped_reports'       => ':count laporan dilewati karena jenis laporannya tidak terpasang pada instansi ini.',
        'unbundled_media'       => ':count berkas disimpan pada penyimpanan jarak jauh dan tidak disertakan; unggah ulang secara manual.',
        'missing_currency_refs' => ':count catatan merujuk pada mata uang yang tidak ada dalam cadangan dan dialihkan ke mata uang bawaan.',
    ],

    'errors' => [
        'invalid_format' => 'Berkas yang diunggah bukan cadangan perusahaan Libre Accounting yang valid.',
        'newer_version'  => 'Cadangan ini dibuat oleh versi Libre Accounting yang lebih baru. Silakan tingkatkan instansi ini sebelum memulihkan.',
    ],

];
