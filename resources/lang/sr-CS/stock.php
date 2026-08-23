<?php

return [

    'stock'                 => 'Zalihe',
    'track_stock'           => 'Prati zalihe',
    'untrack_stock'         => 'Prekini praćenje zaliha',
    'stock_quantity'        => 'Stanje zaliha',
    'opening_count'         => 'Početno stanje',
    'adjust'                => 'Koriguj zalihe',
    'adjustment'            => 'Korekcija',
    'set_to'                => 'Postavi na',
    'change_by'             => 'Promeni za',
    'reason'                => 'Razlog',
    'movements'             => 'Kretanje zaliha',
    'no_movements'          => 'Još nema kretanja zaliha.',
    'via'                   => 'preko :item',
    'components'            => 'Komponente',
    'no_components'         => 'Još nema komponenti.',
    'add_component'         => 'Dodaj komponentu',
    'quantity_per_unit'     => 'Količina po jedinici',
    'build'                 => 'Sklopi',
    'assembly'              => 'Sklop',

    'form_description' => [
        'stock'             => 'Pratite koliko jedinica imate na zalihama. Fakture skidaju jedinice sa zaliha, a računi ih dodaju. Negativne zalihe su dozvoljene.',
        'components'        => 'Navedite stavke od kojih se ova stavka sklapa. Ako ova stavka ne prati zalihe, njenom prodajom se komponente skidaju sa zaliha. Ako prati, koristite Sklopi da biste od komponenti napravili gotove jedinice.',
        'build'             => 'Skida komponente sa zaliha i dodaje gotove jedinice. Negativna količina ponovo rastavlja jedinice na komponente.',
    ],

    'bulk' => [
        'track'             => 'Jeste li sigurni da želite <b>pratiti zalihe</b> za odabrane :type?',
        'untrack'           => 'Jeste li sigurni da želite <b>prekinuti praćenje zaliha</b> za odabrane :type?',
    ],

    'messages' => [
        'adjusted'          => 'Stanje zaliha stavke :item je sada :quantity.',
        'built'             => 'Sklopljeno je :quantity jedinica stavke :item.',
        'tracked'           => 'Praćenje zaliha je uključeno za odabrane stavke.',
        'untracked'         => 'Praćenje zaliha je isključeno za odabrane stavke.',
    ],

    'errors' => [
        'component_is_self' => 'Stavka ne može biti komponenta same sebe.',
        'component_is_kit'  => ':item već ima komponente, pa se ne može koristiti kao komponenta.',
        'kit_as_component'  => ':item se koristi kao komponenta druge stavke, pa ne može imati sopstvene komponente.',
        'not_tracked'       => ':item ne prati zalihe.',
        'no_components'     => ':item nema komponente od kojih bi se sklopila.',
        'not_product'       => ':item je usluga, pa ne može pratiti zalihe.',
    ],

];
