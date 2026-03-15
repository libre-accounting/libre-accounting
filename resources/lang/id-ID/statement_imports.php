<?php

return [

    'form_description'  => 'Unggah rekening koran CAMT.053 (ISO 20022 XML) untuk mengimpor transaksinya. Baris-baris tersebut disiapkan untuk ditinjau sebelum apa pun ditambahkan ke pembukuan Anda.',
    'account'           => 'Akun',
    'file'              => 'Berkas rekening koran',

    'statement'         => 'Rekening koran',
    'period'            => 'Periode',
    'booked_at'         => 'Dibukukan',
    'value_date'        => 'Tanggal valuta',
    'counterparty'      => 'Pihak lawan',
    'remittance'        => 'Informasi pengiriman uang',
    'reference'         => 'Referensi',
    'lines'             => 'Baris',
    'imported'          => 'Diimpor',
    'total_lines'       => 'Total baris',
    'imported_lines'    => 'Baris yang diimpor',

    'statuses' => [
        'pending'   => 'Tertunda',
        'imported'  => 'Diimpor',
        'skipped'   => 'Dilewati',
        'duplicate' => 'Duplikat',
        'reviewing' => 'Sedang ditinjau',
        'completed' => 'Selesai',
    ],

    'import_selected'   => 'Impor yang dipilih',
    'select_all'        => 'Pilih semua',
    'set_category_all'  => 'Atur kategori untuk semua yang dipilih',

    'messages' => [
        'staged'        => ':count baris berhasil diuraikan dan siap untuk ditinjau.',
        'committed'     => ':count transaksi berhasil dibuat.',
        'truncated'     => 'Rekening koran terlalu besar: :count baris yang melebihi batas tidak diimpor.',
        'iban_mismatch' => 'IBAN pada rekening koran tidak cocok dengan akun yang dipilih. Silakan periksa kembali akun tersebut.',
    ],

    'errors' => [
        'not_xml'           => 'Rekening koran harus berupa berkas XML CAMT.053.',
        'unreadable'        => 'Berkas yang diunggah tidak dapat dibaca.',
        'already_imported'  => 'Berkas yang sama persis ini sudah pernah diimpor.',
        'staging_failed'    => 'Rekening koran tidak dapat disiapkan untuk ditinjau. Silakan coba lagi.',
        'none_selected'     => 'Pilih setidaknya satu baris untuk diimpor.',
        'category_required' => 'Pilih kategori untuk setiap baris yang dipilih sebelum mengimpor.',
    ],

];
