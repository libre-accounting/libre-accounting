<?php

return [

    'stock'                 => 'Estoc',
    'track_stock'           => 'Controla l\'estoc',
    'stock_quantity'        => 'Estoc disponible',
    'opening_count'         => 'Estoc inicial',
    'adjust'                => 'Ajusta l\'estoc',
    'adjustment'            => 'Ajust',
    'set_to'                => 'Estableix a',
    'change_by'             => 'Modifica en',
    'reason'                => 'Motiu',
    'movements'             => 'Moviments d\'estoc',
    'no_movements'          => 'Encara no hi ha moviments d\'estoc.',
    'via'                   => 'a través de :item',
    'components'            => 'Components',
    'no_components'         => 'Encara no hi ha components.',
    'add_component'         => 'Afegeix un component',
    'quantity_per_unit'     => 'Quantitat per unitat',
    'build'                 => 'Munta',
    'assembly'              => 'Muntatge',

    'form_description' => [
        'stock'             => 'Controla quantes unitats hi ha en estoc. Les factures de client treuen unitats de l\'estoc i les factures de proveïdor n\'hi afegeixen. L\'estoc negatiu està permès.',
        'components'        => 'Indica els articles amb què es munta aquest article. Si aquest article no controla l\'estoc, vendre\'l treu els components de l\'estoc. Si el controla, fes servir «Munta» per convertir els components en unitats acabades.',
        'build'             => 'Treu els components de l\'estoc i hi afegeix les unitats acabades. Una quantitat negativa torna a desmuntar les unitats.',
    ],

    'messages' => [
        'adjusted'          => 'L\'estoc de :item ara és de :quantity.',
        'built'             => 'S\'han muntat :quantity unitats de :item.',
    ],

    'errors' => [
        'component_is_self' => 'Un article no pot ser component de si mateix.',
        'component_is_kit'  => ':item ja té components, de manera que no es pot fer servir com a component.',
        'kit_as_component'  => ':item es fa servir com a component d\'un altre article, de manera que no pot tenir components propis.',
        'not_tracked'       => ':item no controla l\'estoc.',
        'no_components'     => ':item no té components amb què muntar-lo.',
    ],

];
