<?php

return [

    'stock'                 => 'Stok',
    'track_stock'           => 'Jejak stok',
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

    'messages' => [
        'adjusted'          => 'Stok :item kini :quantity.',
        'built'             => 'Telah membina :quantity unit :item.',
    ],

    'errors' => [
        'component_is_self' => 'Item tidak boleh menjadi komponen dirinya sendiri.',
        'component_is_kit'  => ':item sudah mempunyai komponen, jadi ia tidak boleh digunakan sebagai komponen.',
        'kit_as_component'  => ':item digunakan sebagai komponen item lain, jadi ia tidak boleh mempunyai komponen sendiri.',
        'not_tracked'       => ':item tidak menjejak stok.',
        'no_components'     => ':item tiada komponen untuk dibina.',
    ],

];
