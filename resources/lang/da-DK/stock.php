<?php

return [

    'stock'                 => 'Lager',
    'track_stock'           => 'Lagerstyring',
    'untrack_stock'         => 'Stop lagerstyring',
    'stock_quantity'        => 'Lagerbeholdning',
    'opening_count'         => 'Primobeholdning',
    'adjust'                => 'Regulér lager',
    'adjustment'            => 'Regulering',
    'set_to'                => 'Sæt til',
    'change_by'             => 'Ændr med',
    'reason'                => 'Årsag',
    'movements'             => 'Lagerbevægelser',
    'no_movements'          => 'Ingen lagerbevægelser endnu.',
    'via'                   => 'via :item',
    'components'            => 'Komponenter',
    'no_components'         => 'Ingen komponenter endnu.',
    'add_component'         => 'Tilføj komponent',
    'quantity_per_unit'     => 'Antal pr. enhed',
    'build'                 => 'Saml',
    'assembly'              => 'Samling',

    'form_description' => [
        'stock'             => 'Hold styr på, hvor mange enheder der er på lager. Fakturaer trækker enheder fra lageret, og regninger lægger dem til. Negativ lagerbeholdning er tilladt.',
        'components'        => 'Angiv de varer, som denne vare er sammensat af. Hvis denne vare ikke har lagerstyring, trækkes komponenterne fra lageret, når den sælges. Hvis den har, kan du bruge »Saml« til at omdanne komponenter til færdige enheder.',
        'build'             => 'Trækker komponenterne fra lageret og lægger de færdige enheder til. Et negativt antal skiller enhederne ad igen.',
    ],

    'bulk' => [
        'track'             => 'Er du sikker på du vil <b>slå lagerstyring til</b> for de valgte :type?',
        'untrack'           => 'Er du sikker på du vil <b>slå lagerstyring fra</b> for de valgte :type?',
    ],

    'messages' => [
        'adjusted'          => 'Lagerbeholdningen af :item er nu :quantity.',
        'built'             => 'Der er samlet :quantity stk. :item.',
        'tracked'           => 'Lagerstyring er slået til for de valgte varer.',
        'untracked'         => 'Lagerstyring er slået fra for de valgte varer.',
    ],

    'errors' => [
        'component_is_self' => 'En vare kan ikke være en komponent i sig selv.',
        'component_is_kit'  => ':item har allerede komponenter og kan derfor ikke bruges som komponent.',
        'kit_as_component'  => ':item bruges som komponent i en anden vare og kan derfor ikke selv have komponenter.',
        'not_tracked'       => ':item har ikke lagerstyring.',
        'no_components'     => ':item har ingen komponenter at samle ud fra.',
        'not_product'       => ':item er en ydelse og kan derfor ikke have lagerstyring.',
    ],

];
