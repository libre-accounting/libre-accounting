<?php

return [

    'stock'                 => 'Zalihe',
    'track_stock'           => 'Prati zalihe',
    'stock_quantity'        => 'Stanje zaliha',
    'opening_count'         => 'Početno stanje',
    'adjust'                => 'Uskladi zalihe',
    'adjustment'            => 'Usklađenje',
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
        'stock'             => 'Pratite koliko je jedinica na zalihi. Fakture skidaju jedinice sa zaliha, a računi ih dodaju. Negativne zalihe su dopuštene.',
        'components'        => 'Navedite stavke od kojih se ova stavka sastavlja. Ako ova stavka ne prati zalihe, njezinom prodajom komponente se skidaju sa zaliha. Ako prati, upotrijebite Sastavi kako biste komponente pretvorili u gotove jedinice.',
        'build'             => 'Skida komponente sa zaliha i dodaje gotove jedinice. Negativna količina ponovno rastavlja jedinice.',
    ],

    'messages' => [
        'adjusted'          => 'Zalihe stavke :item sada iznose :quantity.',
        'built'             => 'Sastavljeno :quantity jedinica stavke :item.',
    ],

    'errors' => [
        'component_is_self' => 'Stavka ne može biti komponenta same sebe.',
        'component_is_kit'  => 'Stavka :item već ima komponente pa se ne može koristiti kao komponenta.',
        'kit_as_component'  => 'Stavka :item koristi se kao komponenta druge stavke pa ne može imati vlastite komponente.',
        'not_tracked'       => 'Stavka :item ne prati zalihe.',
        'no_components'     => 'Stavka :item nema komponente od kojih bi se sastavila.',
    ],

];
