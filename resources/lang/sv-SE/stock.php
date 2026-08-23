<?php

return [

    'stock'                 => 'Lager',
    'track_stock'           => 'Lagerförd artikel',
    'untrack_stock'         => 'Sluta lagerföra',
    'stock_quantity'        => 'Lagersaldo',
    'opening_count'         => 'Ingående lagersaldo',
    'adjust'                => 'Justera lager',
    'adjustment'            => 'Justering',
    'set_to'                => 'Sätt till',
    'change_by'             => 'Ändra med',
    'reason'                => 'Orsak',
    'movements'             => 'Lagerrörelser',
    'no_movements'          => 'Inga lagerrörelser ännu.',
    'via'                   => 'via :item',
    'components'            => 'Komponenter',
    'no_components'         => 'Inga komponenter ännu.',
    'add_component'         => 'Lägg till komponent',
    'quantity_per_unit'     => 'Antal per enhet',
    'build'                 => 'Montera',
    'assembly'              => 'Montering',

    'form_description' => [
        'stock'             => 'Håll koll på hur många enheter som finns i lager. Fakturor tar ut enheter ur lagret och räkningar lägger till dem. Negativt lagersaldo är tillåtet.',
        'components'        => 'Ange vilka artiklar den här artikeln byggs av. Om artikeln inte är lagerförd tas komponenterna ur lagret när den säljs. Om den är lagerförd använder du Montera för att göra färdiga enheter av komponenterna.',
        'build'             => 'Tar ut komponenterna ur lagret och lägger till de färdiga enheterna. Ett negativt antal plockar isär enheter igen.',
    ],

    'bulk' => [
        'track'             => 'Är du säker på att du vill <b>lagerföra</b> markerade :type?',
        'untrack'           => 'Är du säker på att du vill <b>sluta lagerföra</b> markerade :type?',
    ],

    'messages' => [
        'adjusted'          => 'Lagersaldot för :item är nu :quantity.',
        'built'             => ':quantity st :item har monterats.',
        'tracked'           => 'Lagerföring har aktiverats för de markerade artiklarna.',
        'untracked'         => 'Lagerföring har inaktiverats för de markerade artiklarna.',
    ],

    'errors' => [
        'component_is_self' => 'En artikel kan inte vara komponent i sig själv.',
        'component_is_kit'  => ':item har redan komponenter och kan därför inte användas som komponent.',
        'kit_as_component'  => ':item används som komponent i en annan artikel och kan därför inte ha egna komponenter.',
        'not_tracked'       => ':item är inte lagerförd.',
        'no_components'     => ':item har inga komponenter att montera av.',
        'not_product'       => ':item är en tjänst och kan därför inte lagerföras.',
    ],

];
