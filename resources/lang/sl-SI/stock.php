<?php

return [

    'stock'                 => 'Zaloga',
    'track_stock'           => 'Spremljaj zalogo',
    'untrack_stock'         => 'Prenehaj spremljati zalogo',
    'stock_quantity'        => 'Stanje zaloge',
    'opening_count'         => 'Začetno stanje',
    'adjust'                => 'Prilagodi zalogo',
    'adjustment'            => 'Prilagoditev',
    'set_to'                => 'Nastavi na',
    'change_by'             => 'Spremeni za',
    'reason'                => 'Razlog',
    'movements'             => 'Premiki zaloge',
    'no_movements'          => 'Še ni premikov zaloge.',
    'via'                   => 'prek :item',
    'components'            => 'Sestavine',
    'no_components'         => 'Še ni sestavin.',
    'add_component'         => 'Dodaj sestavino',
    'quantity_per_unit'     => 'Količina na enoto',
    'build'                 => 'Sestavi',
    'assembly'              => 'Sestavljanje',

    'form_description' => [
        'stock'             => 'Spremljajte, koliko enot je na zalogi. Računi enote iz zaloge odvzamejo, prejeti računi pa jih dodajo. Negativna zaloga je dovoljena.',
        'components'        => 'Navedite artikle, iz katerih je ta artikel sestavljen. Če ta artikel ne spremlja zaloge, se ob njegovi prodaji iz zaloge odvzamejo sestavine. Če jo spremlja, uporabite Sestavi, da sestavine pretvorite v končne enote.',
        'build'             => 'Odvzame sestavine iz zaloge in doda končne enote. Negativna količina enote znova razstavi na sestavine.',
    ],

    'bulk' => [
        'track'             => 'Ali ste prepričani, da želite, da izbrani :type <b>spremljajo zalogo</b>?',
        'untrack'           => 'Ali ste prepričani, da želite, da izbrani :type <b>prenehajo spremljati zalogo</b>?',
    ],

    'messages' => [
        'adjusted'          => 'Zaloga artikla :item je zdaj :quantity.',
        'built'             => 'Sestavljeno :quantity artikla :item.',
        'tracked'           => 'Spremljanje zaloge je vklopljeno za izbrane artikle.',
        'untracked'         => 'Spremljanje zaloge je izklopljeno za izbrane artikle.',
    ],

    'errors' => [
        'component_is_self' => 'Artikel ne more biti sestavina samega sebe.',
        'component_is_kit'  => 'Artikel :item že ima sestavine, zato ga ni mogoče uporabiti kot sestavino.',
        'kit_as_component'  => 'Artikel :item se uporablja kot sestavina drugega artikla, zato ne more imeti lastnih sestavin.',
        'not_tracked'       => 'Artikel :item ne spremlja zaloge.',
        'no_components'     => 'Artikel :item nima sestavin, iz katerih bi ga lahko sestavili.',
        'not_product'       => 'Artikel :item je storitev, zato ne more spremljati zaloge.',
    ],

];
