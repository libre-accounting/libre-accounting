<?php

return [

    'stock'                 => 'Voorraad',
    'track_stock'           => 'Voorraad bijhouden',
    'stock_quantity'        => 'Actuele voorraad',
    'opening_count'         => 'Beginvoorraad',
    'adjust'                => 'Voorraad aanpassen',
    'adjustment'            => 'Aanpassing',
    'set_to'                => 'Instellen op',
    'change_by'             => 'Wijzigen met',
    'reason'                => 'Reden',
    'movements'             => 'Voorraadmutaties',
    'no_movements'          => 'Nog geen voorraadmutaties.',
    'via'                   => 'via :item',
    'components'            => 'Onderdelen',
    'no_components'         => 'Nog geen onderdelen.',
    'add_component'         => 'Onderdeel toevoegen',
    'quantity_per_unit'     => 'Aantal per eenheid',
    'build'                 => 'Samenstellen',
    'assembly'              => 'Samenstelling',

    'form_description' => [
        'stock'             => 'Houd bij hoeveel eenheden er op voorraad zijn. Facturen halen eenheden uit de voorraad en rekeningen voegen ze toe. Negatieve voorraad is toegestaan.',
        'components'        => 'Geef op uit welke artikelen dit artikel is samengesteld. Als dit artikel geen voorraad bijhoudt, worden bij verkoop de onderdelen uit de voorraad gehaald. Houdt het wel voorraad bij, gebruik dan Samenstellen om onderdelen om te zetten in gerede eenheden.',
        'build'             => 'Haalt de onderdelen uit de voorraad en voegt de gerede eenheden toe. Een negatief aantal haalt de eenheden weer uit elkaar.',
    ],

    'messages' => [
        'adjusted'          => 'De voorraad van :item is nu :quantity.',
        'built'             => ':quantity stuks van :item samengesteld.',
    ],

    'errors' => [
        'component_is_self' => 'Een artikel kan geen onderdeel van zichzelf zijn.',
        'component_is_kit'  => ':item heeft al onderdelen en kan daarom niet als onderdeel worden gebruikt.',
        'kit_as_component'  => ':item wordt gebruikt als onderdeel van een ander artikel en kan daarom geen eigen onderdelen hebben.',
        'not_tracked'       => ':item houdt geen voorraad bij.',
        'no_components'     => ':item heeft geen onderdelen om uit samen te stellen.',
    ],

];
