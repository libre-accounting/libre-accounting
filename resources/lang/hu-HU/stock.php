<?php

return [

    'stock'                 => 'Készlet',
    'track_stock'           => 'Készlet követése',
    'untrack_stock'         => 'Készlet követésének leállítása',
    'stock_quantity'        => 'Aktuális készlet',
    'opening_count'         => 'Nyitó készlet',
    'adjust'                => 'Készletkorrekció',
    'adjustment'            => 'Korrekció',
    'set_to'                => 'Beállítás erre',
    'change_by'             => 'Módosítás ennyivel',
    'reason'                => 'Indok',
    'movements'             => 'Készletmozgások',
    'no_movements'          => 'Még nincs készletmozgás.',
    'via'                   => ':item által',
    'components'            => 'Összetevők',
    'no_components'         => 'Még nincsenek összetevők.',
    'add_component'         => 'Összetevő hozzáadása',
    'quantity_per_unit'     => 'Mennyiség egységenként',
    'build'                 => 'Összeállítás',
    'assembly'              => 'Összeszerelés',

    'form_description' => [
        'stock'             => 'Kövesse nyomon, hány egység van készleten. A kimenő számlák csökkentik, a bejövő számlák növelik a készletet. Negatív készlet megengedett.',
        'components'        => 'Sorolja fel azokat a tételeket, amelyekből ez a tétel összeáll. Ha ez a tétel nem követi a készletet, eladásakor az összetevők kerülnek le a készletről. Ha követi, az Összeállítás gombbal alakíthatja az összetevőket kész egységekké.',
        'build'             => 'Leveszi az összetevőket a készletről, és hozzáadja a kész egységeket. Negatív mennyiség esetén az egységeket újra összetevőire bontja.',
    ],

    'bulk' => [
        'track'             => 'Biztosan <b>követi a készletet</b> a kiválasztott :type esetében?',
        'untrack'           => 'Biztosan <b>leállítja a készlet követését</b> a kiválasztott :type esetében?',
    ],

    'messages' => [
        'adjusted'          => ':item készlete mostantól :quantity.',
        'built'             => ':quantity db :item összeállítva.',
        'tracked'           => 'A készletkövetés be lett kapcsolva a kiválasztott tételeknél.',
        'untracked'         => 'A készletkövetés ki lett kapcsolva a kiválasztott tételeknél.',
    ],

    'errors' => [
        'component_is_self' => 'Egy tétel nem lehet saját maga összetevője.',
        'component_is_kit'  => ':item már rendelkezik összetevőkkel, ezért nem használható összetevőként.',
        'kit_as_component'  => ':item egy másik tétel összetevőjeként szerepel, ezért nem lehetnek saját összetevői.',
        'not_tracked'       => ':item nem követi a készletet.',
        'no_components'     => ':item nem rendelkezik összetevőkkel, amelyekből összeállítható lenne.',
        'not_product'       => ':item szolgáltatás, ezért nem követheti a készletet.',
    ],

];
