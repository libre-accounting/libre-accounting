<?php

return [

    'stock'                 => 'Stoku',
    'track_stock'           => 'Gjurmo stokun',
    'stock_quantity'        => 'Gjendja e stokut',
    'opening_count'         => 'Gjendja fillestare',
    'adjust'                => 'Rregullo stokun',
    'adjustment'            => 'Rregullim',
    'set_to'                => 'Vendos në',
    'change_by'             => 'Ndrysho me',
    'reason'                => 'Arsyeja',
    'movements'             => 'Lëvizjet e stokut',
    'no_movements'          => 'Ende nuk ka lëvizje stoku.',
    'via'                   => 'përmes :item',
    'components'            => 'Përbërësit',
    'no_components'         => 'Ende nuk ka përbërës.',
    'add_component'         => 'Shto përbërës',
    'quantity_per_unit'     => 'Sasia për njësi',
    'build'                 => 'Monto',
    'assembly'              => 'Montim',

    'form_description' => [
        'stock'             => 'Gjurmoni sa njësi keni në stok. Faturat e shitjes nxjerrin njësi nga stoku dhe faturat e blerjes i shtojnë. Stoku negativ lejohet.',
        'components'        => 'Listoni artikujt nga të cilët përbëhet ky artikull. Nëse ky artikull nuk e gjurmon stokun, shitja e tij i nxjerr përbërësit nga stoku. Nëse e gjurmon, përdorni Monto për t\'i kthyer përbërësit në njësi të gatshme.',
        'build'             => 'I nxjerr përbërësit nga stoku dhe shton njësitë e gatshme. Një sasi negative i çmonton përsëri njësitë.',
    ],

    'messages' => [
        'adjusted'          => 'Stoku i :item tani është :quantity.',
        'built'             => 'U montuan :quantity njësi të :item.',
    ],

    'errors' => [
        'component_is_self' => 'Një artikull nuk mund të jetë përbërës i vetvetes.',
        'component_is_kit'  => ':item ka tashmë përbërës, prandaj nuk mund të përdoret si përbërës.',
        'kit_as_component'  => ':item përdoret si përbërës i një artikulli tjetër, prandaj nuk mund të ketë përbërësit e vet.',
        'not_tracked'       => ':item nuk e gjurmon stokun.',
        'no_components'     => ':item nuk ka përbërës për t\'u montuar.',
    ],

];
