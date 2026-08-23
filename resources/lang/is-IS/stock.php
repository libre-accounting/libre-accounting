<?php

return [

    'stock'                 => 'Birgðir',
    'track_stock'           => 'Fylgjast með birgðum',
    'untrack_stock'         => 'Hætta að fylgjast með birgðum',
    'stock_quantity'        => 'Birgðastaða',
    'opening_count'         => 'Upphafsstaða',
    'adjust'                => 'Leiðrétta birgðir',
    'adjustment'            => 'Leiðrétting',
    'set_to'                => 'Setja í',
    'change_by'             => 'Breyta um',
    'reason'                => 'Ástæða',
    'movements'             => 'Birgðahreyfingar',
    'no_movements'          => 'Engar birgðahreyfingar enn.',
    'via'                   => 'í gegnum :item',
    'components'            => 'Íhlutir',
    'no_components'         => 'Engir íhlutir enn.',
    'add_component'         => 'Bæta við íhlut',
    'quantity_per_unit'     => 'Magn á hverja einingu',
    'build'                 => 'Setja saman',
    'assembly'              => 'Samsetning',

    'form_description' => [
        'stock'             => 'Haltu utan um hversu margar einingar eru til í birgðum. Sölureikningar taka einingar úr birgðum og kaupreikningar bæta þeim við. Neikvæð birgðastaða er leyfð.',
        'components'        => 'Listaðu upp vörurnar sem þessi vara er sett saman úr. Ef þessi vara heldur ekki utan um birgðir eru íhlutirnir teknir úr birgðum þegar hún er seld. Ef hún gerir það skaltu nota Setja saman til að breyta íhlutum í fullbúnar einingar.',
        'build'             => 'Tekur íhlutina úr birgðum og bætir fullbúnu einingunum við. Neikvætt magn tekur einingarnar í sundur aftur.',
    ],

    'bulk' => [
        'track'             => 'Ertu viss um að þú viljir <b>fylgjast með birgðum</b> fyrir valdar :type?',
        'untrack'           => 'Ertu viss um að þú viljir <b>hætta að fylgjast með birgðum</b> fyrir valdar :type?',
    ],

    'messages' => [
        'adjusted'          => 'Birgðastaða :item er nú :quantity.',
        'built'             => ':quantity einingar af :item settar saman.',
        'tracked'           => 'Kveikt hefur verið á birgðaeftirliti fyrir valdar vörur.',
        'untracked'         => 'Slökkt hefur verið á birgðaeftirliti fyrir valdar vörur.',
    ],

    'errors' => [
        'component_is_self' => 'Vara getur ekki verið íhlutur í sjálfri sér.',
        'component_is_kit'  => ':item hefur þegar íhluti og er því ekki hægt að nota sem íhlut.',
        'kit_as_component'  => ':item er notuð sem íhlutur í annarri vöru og getur því ekki haft eigin íhluti.',
        'not_tracked'       => ':item heldur ekki utan um birgðir.',
        'no_components'     => ':item hefur enga íhluti til að setja saman úr.',
        'not_product'       => ':item er þjónusta og getur því ekki haldið utan um birgðir.',
    ],

];
