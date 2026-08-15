<?php

return [

    'stock'                 => 'Lager',
    'track_stock'           => 'Følg lagerbeholdning',
    'stock_quantity'        => 'Lagerbeholdning',
    'opening_count'         => 'Inngående beholdning',
    'adjust'                => 'Juster lager',
    'adjustment'            => 'Justering',
    'set_to'                => 'Sett til',
    'change_by'             => 'Endre med',
    'reason'                => 'Årsak',
    'movements'             => 'Lagerbevegelser',
    'no_movements'          => 'Ingen lagerbevegelser ennå.',
    'via'                   => 'via :item',
    'components'            => 'Komponenter',
    'no_components'         => 'Ingen komponenter ennå.',
    'add_component'         => 'Legg til komponent',
    'quantity_per_unit'     => 'Antall per enhet',
    'build'                 => 'Bygg',
    'assembly'              => 'Montering',

    'form_description' => [
        'stock'             => 'Følg med på hvor mange enheter som er på lager. Fakturaer tar enheter ut, og regninger tar dem inn. Negativ beholdning er tillatt.',
        'components'        => 'List opp artiklene denne er bygd av. Hvis denne artikkelen ikke følger lagerbeholdning, tas komponentene ut av lageret når den selges. Hvis den gjør det, bruk Bygg for å gjøre komponenter om til ferdige enheter.',
        'build'             => 'Tar komponentene ut av lageret og legger til de ferdige enhetene. Et negativt antall tar enhetene fra hverandre igjen.',
    ],

    'messages' => [
        'adjusted'          => 'Lagerbeholdningen for :item er nå :quantity.',
        'built'             => 'Bygde :quantity av :item.',
    ],

    'errors' => [
        'component_is_self' => 'En artikkel kan ikke være komponent i seg selv.',
        'component_is_kit'  => ':item har allerede komponenter og kan derfor ikke brukes som komponent.',
        'kit_as_component'  => ':item brukes som komponent i en annen artikkel og kan derfor ikke ha egne komponenter.',
        'not_tracked'       => ':item følger ikke lagerbeholdning.',
        'no_components'     => ':item har ingen komponenter å bygge fra.',
    ],

];
