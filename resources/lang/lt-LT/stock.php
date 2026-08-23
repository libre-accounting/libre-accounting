<?php

return [

    'stock'                 => 'Atsargos',
    'track_stock'           => 'Sekti atsargas',
    'untrack_stock'         => 'Nustoti sekti atsargas',
    'stock_quantity'        => 'Atsargų likutis',
    'opening_count'         => 'Pradinis likutis',
    'adjust'                => 'Koreguoti atsargas',
    'adjustment'            => 'Koregavimas',
    'set_to'                => 'Nustatyti į',
    'change_by'             => 'Pakeisti per',
    'reason'                => 'Priežastis',
    'movements'             => 'Atsargų judėjimas',
    'no_movements'          => 'Atsargų judėjimų dar nėra.',
    'via'                   => 'per :item',
    'components'            => 'Komponentai',
    'no_components'         => 'Komponentų dar nėra.',
    'add_component'         => 'Pridėti komponentą',
    'quantity_per_unit'     => 'Kiekis vienetui',
    'build'                 => 'Surinkti',
    'assembly'              => 'Surinkimas',

    'form_description' => [
        'stock'             => 'Sekite, kiek vienetų yra atsargose. Sąskaitos faktūros vienetus nurašo, o pirkimų sąskaitos juos užpajamuoja. Neigiamas likutis leidžiamas.',
        'components'        => 'Nurodykite prekes, iš kurių sudaryta ši prekė. Jei šios prekės atsargos nesekamos, ją pardavus iš atsargų nurašomi komponentai. Jei sekamos, naudokite „Surinkti“, kad komponentus paverstumėte gatavais vienetais.',
        'build'             => 'Nurašo komponentus iš atsargų ir užpajamuoja gatavus vienetus. Neigiamas kiekis vienetus vėl išardo į komponentus.',
    ],

    'bulk' => [
        'track'             => 'Ar tikrai norite, kad pasirinktos :type <b>sektų atsargas</b>?',
        'untrack'           => 'Ar tikrai norite, kad pasirinktos :type <b>nustotų sekti atsargas</b>?',
    ],

    'messages' => [
        'adjusted'          => 'Prekės :item atsargų likutis dabar :quantity.',
        'built'             => 'Surinkta :quantity vnt. prekės :item.',
        'tracked'           => 'Pasirinktoms prekėms įjungtas atsargų sekimas.',
        'untracked'         => 'Pasirinktoms prekėms išjungtas atsargų sekimas.',
    ],

    'errors' => [
        'component_is_self' => 'Prekė negali būti pati savo komponentas.',
        'component_is_kit'  => 'Prekė :item jau turi komponentų, todėl negali būti naudojama kaip komponentas.',
        'kit_as_component'  => 'Prekė :item naudojama kaip kitos prekės komponentas, todėl negali turėti savo komponentų.',
        'not_tracked'       => 'Prekės :item atsargos nesekamos.',
        'no_components'     => 'Prekė :item neturi komponentų, iš kurių būtų galima ją surinkti.',
        'not_product'       => ':item yra paslauga, todėl jos atsargų sekti negalima.',
    ],

];
