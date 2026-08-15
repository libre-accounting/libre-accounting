<?php

return [

    'stock'                 => 'Krājumi',
    'track_stock'           => 'Uzskaitīt krājumus',
    'stock_quantity'        => 'Krājumu atlikums',
    'opening_count'         => 'Sākuma atlikums',
    'adjust'                => 'Koriģēt krājumus',
    'adjustment'            => 'Korekcija',
    'set_to'                => 'Iestatīt uz',
    'change_by'             => 'Mainīt par',
    'reason'                => 'Iemesls',
    'movements'             => 'Krājumu kustība',
    'no_movements'          => 'Krājumu kustības vēl nav.',
    'via'                   => 'caur :item',
    'components'            => 'Sastāvdaļas',
    'no_components'         => 'Sastāvdaļu vēl nav.',
    'add_component'         => 'Pievienot sastāvdaļu',
    'quantity_per_unit'     => 'Daudzums uz vienību',
    'build'                 => 'Komplektēt',
    'assembly'              => 'Komplektēšana',

    'form_description' => [
        'stock'             => 'Uzskaitiet, cik vienību ir krājumā. Rēķini vienības no krājumiem izņem, bet pavadzīmes tās pievieno. Negatīvs krājumu atlikums ir atļauts.',
        'components'        => 'Norādiet preces, no kurām šī prece sastāv. Ja šai precei krājumi netiek uzskaitīti, to pārdodot no krājumiem tiek izņemtas sastāvdaļas. Ja tiek uzskaitīti, izmantojiet „Komplektēt”, lai sastāvdaļas pārvērstu gatavās vienībās.',
        'build'             => 'Izņem sastāvdaļas no krājumiem un pievieno gatavās vienības. Negatīvs daudzums vienības atkal izjauc sastāvdaļās.',
    ],

    'messages' => [
        'adjusted'          => 'Preces :item krājumu atlikums tagad ir :quantity.',
        'built'             => 'Komplektēšana pabeigta: :item, :quantity gab.',
    ],

    'errors' => [
        'component_is_self' => 'Prece nevar būt pati sava sastāvdaļa.',
        'component_is_kit'  => 'Precei :item jau ir sastāvdaļas, tāpēc to nevar izmantot kā sastāvdaļu.',
        'kit_as_component'  => 'Prece :item tiek izmantota kā citas preces sastāvdaļa, tāpēc tai nevar būt savu sastāvdaļu.',
        'not_tracked'       => 'Precei :item krājumi netiek uzskaitīti.',
        'no_components'     => 'Precei :item nav sastāvdaļu, no kurām to komplektēt.',
    ],

];
