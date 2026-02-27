<?php

return [

    'form_description'  => 'Muat naik penyata bank CAMT.053 (XML ISO 20022) untuk mengimport transaksinya. Baris akan dipentaskan untuk semakan sebelum apa-apa ditambah ke dalam akaun anda.',
    'account'           => 'Akaun',
    'file'              => 'Fail penyata',

    'statement'         => 'Penyata',
    'period'            => 'Tempoh',
    'booked_at'         => 'Dicatat',
    'value_date'        => 'Tarikh nilai',
    'counterparty'      => 'Pihak lawan',
    'remittance'        => 'Maklumat kiriman wang',
    'reference'         => 'Rujukan',
    'lines'             => 'Baris',
    'imported'          => 'Diimport',
    'total_lines'       => 'Jumlah baris',
    'imported_lines'    => 'Baris diimport',

    'statuses' => [
        'pending'   => 'Menunggu',
        'imported'  => 'Diimport',
        'skipped'   => 'Dilangkau',
        'duplicate' => 'Pendua',
        'reviewing' => 'Sedang disemak',
        'completed' => 'Selesai',
    ],

    'import_selected'   => 'Import yang dipilih',
    'select_all'        => 'Pilih semua',
    'set_category_all'  => 'Tetapkan kategori untuk semua yang dipilih',

    'messages' => [
        'staged'        => ':count baris dihurai dan sedia untuk semakan.',
        'committed'     => ':count transaksi dicipta.',
        'truncated'     => 'Penyata terlalu besar: :count baris melebihi had tidak diimport.',
        'iban_mismatch' => 'IBAN penyata tidak sepadan dengan akaun yang dipilih. Sila semak semula akaun tersebut.',
    ],

    'errors' => [
        'not_xml'           => 'Penyata mestilah fail XML CAMT.053.',
        'unreadable'        => 'Fail yang dimuat naik tidak dapat dibaca.',
        'already_imported'  => 'Fail yang sama ini telah pun diimport.',
        'none_selected'     => 'Pilih sekurang-kurangnya satu baris untuk diimport.',
        'category_required' => 'Pilih kategori untuk setiap baris yang dipilih sebelum mengimport.',
    ],

];
