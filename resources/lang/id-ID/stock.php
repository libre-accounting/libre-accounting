<?php

return [

    'stock'                 => 'Stok',
    'track_stock'           => 'Lacak Stok',
    'untrack_stock'         => 'Berhenti Melacak Stok',
    'stock_quantity'        => 'Stok Tersedia',
    'opening_count'         => 'Stok Awal',
    'adjust'                => 'Sesuaikan Stok',
    'adjustment'            => 'Penyesuaian',
    'set_to'                => 'Atur menjadi',
    'change_by'             => 'Ubah sebesar',
    'reason'                => 'Alasan',
    'movements'             => 'Mutasi Stok',
    'no_movements'          => 'Belum ada mutasi stok.',
    'via'                   => 'melalui :item',
    'components'            => 'Komponen',
    'no_components'         => 'Belum ada komponen.',
    'add_component'         => 'Tambah Komponen',
    'quantity_per_unit'     => 'Kuantitas per Unit',
    'build'                 => 'Rakit',
    'assembly'              => 'Perakitan',

    'form_description' => [
        'stock'             => 'Lacak berapa unit yang ada di stok. Faktur mengurangi stok dan tagihan menambahkannya. Stok negatif diperbolehkan.',
        'components'        => 'Daftarkan item-item penyusun item ini. Jika item ini tidak melacak stok, penjualannya akan mengurangi komponen dari stok. Jika melacak, gunakan Rakit untuk mengubah komponen menjadi unit jadi.',
        'build'             => 'Mengurangi komponen dari stok dan menambahkan unit jadi. Kuantitas negatif mengurai unit kembali menjadi komponen.',
    ],

    'bulk' => [
        'track'             => 'Yakin ingin <b>melacak stok</b> untuk :type yang dipilih?',
        'untrack'           => 'Yakin ingin <b>berhenti melacak stok</b> untuk :type yang dipilih?',
    ],

    'messages' => [
        'adjusted'          => 'Stok :item sekarang :quantity.',
        'built'             => 'Berhasil merakit :quantity unit :item.',
        'tracked'           => 'Pelacakan stok diaktifkan untuk item yang dipilih.',
        'untracked'         => 'Pelacakan stok dinonaktifkan untuk item yang dipilih.',
    ],

    'errors' => [
        'component_is_self' => 'Item tidak dapat menjadi komponen dari dirinya sendiri.',
        'component_is_kit'  => ':item sudah memiliki komponen, sehingga tidak dapat digunakan sebagai komponen.',
        'kit_as_component'  => ':item digunakan sebagai komponen item lain, sehingga tidak dapat memiliki komponen sendiri.',
        'not_tracked'       => ':item tidak melacak stok.',
        'no_components'     => ':item tidak memiliki komponen untuk dirakit.',
        'not_product'       => ':item adalah jasa, sehingga tidak dapat melacak stok.',
    ],

];
