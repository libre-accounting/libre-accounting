<?php

return [

    'stock'                 => 'Stok',
    'track_stock'           => 'Stok takibi',
    'untrack_stock'         => 'Stok takibini durdur',
    'stock_quantity'        => 'Mevcut stok',
    'opening_count'         => 'Açılış stoğu',
    'adjust'                => 'Stoğu düzelt',
    'adjustment'            => 'Düzeltme',
    'set_to'                => 'Şuna ayarla',
    'change_by'             => 'Şu kadar değiştir',
    'reason'                => 'Neden',
    'movements'             => 'Stok hareketleri',
    'no_movements'          => 'Henüz stok hareketi yok.',
    'via'                   => ':item üzerinden',
    'components'            => 'Bileşenler',
    'no_components'         => 'Henüz bileşen yok.',
    'add_component'         => 'Bileşen ekle',
    'quantity_per_unit'     => 'Birim başına miktar',
    'build'                 => 'Üret',
    'assembly'              => 'Montaj',

    'form_description' => [
        'stock'             => 'Stokta kaç adet bulunduğunu takip edin. Satış faturaları stoktan düşer, alış faturaları stoğa ekler. Negatif stoğa izin verilir.',
        'components'        => 'Bu ürünün hangi ürünlerden oluştuğunu listeleyin. Bu ürün stok takibi yapmıyorsa, satıldığında bileşenleri stoktan düşülür. Yapıyorsa, bileşenleri bitmiş ürüne dönüştürmek için Üret seçeneğini kullanın.',
        'build'             => 'Bileşenleri stoktan düşer ve bitmiş adetleri stoğa ekler. Negatif miktar, adetleri yeniden bileşenlerine ayırır.',
    ],

    'bulk' => [
        'track'             => 'Seçili :type için <b>stok takibi yapmak</b> istediğinizden emin misiniz?',
        'untrack'           => 'Seçili :type için <b>stok takibini durdurmak</b> istediğinizden emin misiniz?',
    ],

    'messages' => [
        'adjusted'          => ':item stoğu artık :quantity.',
        'built'             => ':quantity adet :item üretildi.',
        'tracked'           => 'Seçili ürünler için stok takibi açıldı.',
        'untracked'         => 'Seçili ürünler için stok takibi kapatıldı.',
    ],

    'errors' => [
        'component_is_self' => 'Bir ürün kendisinin bileşeni olamaz.',
        'component_is_kit'  => ':item zaten bileşenlere sahip olduğundan bileşen olarak kullanılamaz.',
        'kit_as_component'  => ':item başka bir ürünün bileşeni olarak kullanıldığından kendi bileşenleri olamaz.',
        'not_tracked'       => ':item stok takibi yapmıyor.',
        'no_components'     => ':item için üretimde kullanılacak bileşen yok.',
        'not_product'       => ':item bir hizmet olduğundan stok takibi yapamaz.',
    ],

];
