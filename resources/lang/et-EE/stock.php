<?php

return [

    'stock'                 => 'Ladu',
    'track_stock'           => 'Jälgi laoseisu',
    'stock_quantity'        => 'Laoseis',
    'opening_count'         => 'Algseis',
    'adjust'                => 'Korrigeeri laoseisu',
    'adjustment'            => 'Korrigeerimine',
    'set_to'                => 'Määra koguseks',
    'change_by'             => 'Muuda koguse võrra',
    'reason'                => 'Põhjus',
    'movements'             => 'Laoliikumised',
    'no_movements'          => 'Laoliikumisi veel pole.',
    'via'                   => ':item kaudu',
    'components'            => 'Komponendid',
    'no_components'         => 'Komponente veel pole.',
    'add_component'         => 'Lisa komponent',
    'quantity_per_unit'     => 'Kogus ühiku kohta',
    'build'                 => 'Komplekteeri',
    'assembly'              => 'Komplekteerimine',

    'form_description' => [
        'stock'             => 'Jälgige, mitu ühikut on laos. Arved võtavad ühikuid laost välja ja ostuarved toovad neid juurde. Negatiivne laoseis on lubatud.',
        'components'        => 'Loetlege esemed, millest see ese kokku pannakse. Kui see ese laoseisu ei jälgi, võetakse selle müümisel komponendid laost välja. Kui jälgib, kasutage nuppu Komplekteeri, et teha komponentidest valmis ühikud.',
        'build'             => 'Võtab komponendid laost välja ja lisab valmis ühikud. Negatiivne kogus võtab ühikud uuesti osadeks lahti.',
    ],

    'messages' => [
        'adjusted'          => 'Eseme :item laoseis on nüüd :quantity.',
        'built'             => 'Komplekteeriti :quantity tk eset :item.',
    ],

    'errors' => [
        'component_is_self' => 'Ese ei saa olla iseenda komponent.',
        'component_is_kit'  => 'Esemel :item on juba komponendid, seega ei saa seda komponendina kasutada.',
        'kit_as_component'  => 'Eset :item kasutatakse teise eseme komponendina, seega ei saa sellel endal komponente olla.',
        'not_tracked'       => 'Ese :item ei jälgi laoseisu.',
        'no_components'     => 'Esemel :item pole komponente, millest komplekteerida.',
    ],

];
