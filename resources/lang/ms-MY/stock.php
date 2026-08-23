<?php

return [

    'stock'                 => 'Stok',
    'track_stock'           => 'Jejak stok',
    'untrack_stock'         => 'Berhenti menjejak stok',
    'stock_quantity'        => 'Baki stok',
    'opening_count'         => 'Stok awal',
    'adjust'                => 'Laraskan stok',
    'adjustment'            => 'Pelarasan',
    'set_to'                => 'Tetapkan kepada',
    'change_by'             => 'Ubah sebanyak',
    'reason'                => 'Sebab',
    'movements'             => 'Pergerakan stok',
    'no_movements'          => 'Belum ada pergerakan stok.',
    'via'                   => 'melalui :item',
    'components'            => 'Komponen',
    'no_components'         => 'Belum ada komponen.',
    'add_component'         => 'Tambah komponen',
    'quantity_per_unit'     => 'Kuantiti seunit',
    'build'                 => 'Bina',
    'assembly'              => 'Pemasangan',

    'form_description' => [
        'stock'             => 'Jejak berapa unit yang ada dalam stok. Invois mengeluarkan unit daripada stok dan bil memasukkannya. Stok negatif dibenarkan.',
        'components'        => 'Senaraikan item yang membentuk item ini. Jika item ini tidak menjejak stok, menjualnya akan mengeluarkan komponen daripada stok. Jika ya, gunakan Bina untuk menukar komponen kepada unit siap.',
        'build'             => 'Mengeluarkan komponen daripada stok dan menambah unit siap. Kuantiti negatif merombak semula unit tersebut kepada komponen.',
    ],

    'bulk' => [
        'track'             => 'Adakah anda pasti anda mahu <b>menjejak stok</b> untuk :type yang dipilih?',
        'untrack'           => 'Adakah anda pasti anda mahu <b>berhenti menjejak stok</b> untuk :type yang dipilih?',
    ],

    'messages' => [
        'adjusted'          => 'Stok :item kini :quantity.',
        'built'             => 'Telah membina :quantity unit :item.',
        'tracked'           => 'Penjejakan stok dihidupkan untuk item yang dipilih.',
        'untracked'         => 'Penjejakan stok dimatikan untuk item yang dipilih.',
    ],

    'errors' => [
        'component_is_self' => 'Item tidak boleh menjadi komponen dirinya sendiri.',
        'component_is_kit'  => ':item sudah mempunyai komponen, jadi ia tidak boleh digunakan sebagai komponen.',
        'kit_as_component'  => ':item digunakan sebagai komponen item lain, jadi ia tidak boleh mempunyai komponen sendiri.',
        'not_tracked'       => ':item tidak menjejak stok.',
        'no_components'     => ':item tiada komponen untuk dibina.',
        'not_product'       => ':item ialah perkhidmatan, jadi ia tidak boleh menjejak stok.',
    ],

];
