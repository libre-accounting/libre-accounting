<?php

return [

    'stock'                 => 'Stoc',
    'track_stock'           => 'Urmărește stocul',
    'stock_quantity'        => 'Stoc curent',
    'opening_count'         => 'Stoc inițial',
    'adjust'                => 'Ajustează stocul',
    'adjustment'            => 'Ajustare',
    'set_to'                => 'Setează la',
    'change_by'             => 'Modifică cu',
    'reason'                => 'Motiv',
    'movements'             => 'Mișcări de stoc',
    'no_movements'          => 'Nu există încă mișcări de stoc.',
    'via'                   => 'prin :item',
    'components'            => 'Componente',
    'no_components'         => 'Nu există încă componente.',
    'add_component'         => 'Adaugă componentă',
    'quantity_per_unit'     => 'Cantitate pe unitate',
    'build'                 => 'Asamblează',
    'assembly'              => 'Asamblare',

    'form_description' => [
        'stock'             => 'Urmărește câte unități sunt în stoc. Facturile scad unități din stoc, iar facturile de achiziții le adaugă. Stocul negativ este permis.',
        'components'        => 'Listează articolele din care este alcătuit acest articol. Dacă acest articol nu urmărește stocul, vânzarea lui scade componentele din stoc. Dacă îl urmărește, folosește Asamblează pentru a transforma componentele în unități finite.',
        'build'             => 'Scade componentele din stoc și adaugă unitățile finite. O cantitate negativă dezasamblează unitățile la loc.',
    ],

    'messages' => [
        'adjusted'          => 'Stocul pentru :item este acum :quantity.',
        'built'             => 'S-au asamblat :quantity din :item.',
    ],

    'errors' => [
        'component_is_self' => 'Un articol nu poate fi componenta lui însuși.',
        'component_is_kit'  => ':item are deja componente, deci nu poate fi folosit drept componentă.',
        'kit_as_component'  => ':item este folosit drept componentă a altui articol, deci nu poate avea propriile componente.',
        'not_tracked'       => ':item nu urmărește stocul.',
        'no_components'     => ':item nu are componente din care să fie asamblat.',
    ],

];
