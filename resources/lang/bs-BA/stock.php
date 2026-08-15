<?php

return [

    'stock'                 => 'Zalihe',
    'track_stock'           => 'Prati zalihe',
    'stock_quantity'        => 'Stanje zaliha',
    'opening_count'         => 'Početno stanje',
    'adjust'                => 'Koriguj zalihe',
    'adjustment'            => 'Korekcija',
    'set_to'                => 'Postavi na',
    'change_by'             => 'Promijeni za',
    'reason'                => 'Razlog',
    'movements'             => 'Kretanje zaliha',
    'no_movements'          => 'Još nema kretanja zaliha.',
    'via'                   => 'putem :item',
    'components'            => 'Komponente',
    'no_components'         => 'Još nema komponenti.',
    'add_component'         => 'Dodaj komponentu',
    'quantity_per_unit'     => 'Količina po jedinici',
    'build'                 => 'Sastavi',
    'assembly'              => 'Sastavljanje',

    'form_description' => [
        'stock'             => 'Pratite koliko je jedinica na zalihama. Fakture skidaju jedinice sa zaliha, a računi ih dodaju. Negativne zalihe su dozvoljene.',
        'components'        => 'Navedite artikle od kojih se sastavlja ovaj artikal. Ako ovaj artikal ne prati zalihe, njegova prodaja skida komponente sa zaliha. Ako prati, koristite „Sastavi“ da komponente pretvorite u gotove jedinice.',
        'build'             => 'Skida komponente sa zaliha i dodaje gotove jedinice. Negativna količina ponovo rastavlja jedinice.',
    ],

    'messages' => [
        'adjusted'          => 'Zalihe artikla :item sada iznose :quantity.',
        'built'             => 'Sastavljeno :quantity kom. artikla :item.',
    ],

    'errors' => [
        'component_is_self' => 'Artikal ne može biti komponenta samog sebe.',
        'component_is_kit'  => ':item već ima komponente, pa se ne može koristiti kao komponenta.',
        'kit_as_component'  => ':item se koristi kao komponenta drugog artikla, pa ne može imati vlastite komponente.',
        'not_tracked'       => ':item ne prati zalihe.',
        'no_components'     => ':item nema komponenti od kojih bi se sastavio.',
    ],

];
