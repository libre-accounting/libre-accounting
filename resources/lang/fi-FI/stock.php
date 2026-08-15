<?php

return [

    'stock'                 => 'Varasto',
    'track_stock'           => 'Seuraa varastoa',
    'stock_quantity'        => 'Varastosaldo',
    'opening_count'         => 'Alkusaldo',
    'adjust'                => 'Korjaa varastosaldoa',
    'adjustment'            => 'Korjaus',
    'set_to'                => 'Aseta arvoon',
    'change_by'             => 'Muuta määrällä',
    'reason'                => 'Syy',
    'movements'             => 'Varastotapahtumat',
    'no_movements'          => 'Ei vielä varastotapahtumia.',
    'via'                   => 'tuotteen :item kautta',
    'components'            => 'Komponentit',
    'no_components'         => 'Ei vielä komponentteja.',
    'add_component'         => 'Lisää komponentti',
    'quantity_per_unit'     => 'Määrä yksikköä kohden',
    'build'                 => 'Kokoa',
    'assembly'              => 'Kokoonpano',

    'form_description' => [
        'stock'             => 'Seuraa, montako yksikköä on varastossa. Myyntilaskut vähentävät varastoa ja ostolaskut lisäävät sitä. Negatiivinen varastosaldo on sallittu.',
        'components'        => 'Luettele tuotteet, joista tämä tuote kootaan. Jos tämä tuote ei seuraa varastoa, sen myynti vähentää komponentit varastosta. Jos seuraa, käytä Kokoa-toimintoa muuttaaksesi komponentit valmiiksi yksiköiksi.',
        'build'             => 'Vähentää komponentit varastosta ja lisää valmiit yksiköt. Negatiivinen määrä purkaa yksiköt takaisin komponenteiksi.',
    ],

    'messages' => [
        'adjusted'          => 'Tuotteen :item varastosaldo on nyt :quantity.',
        'built'             => 'Koottiin :quantity kpl tuotetta :item.',
    ],

    'errors' => [
        'component_is_self' => 'Tuote ei voi olla oma komponenttinsa.',
        'component_is_kit'  => 'Tuotteella :item on jo komponentteja, joten sitä ei voi käyttää komponenttina.',
        'kit_as_component'  => 'Tuotetta :item käytetään toisen tuotteen komponenttina, joten sillä ei voi olla omia komponentteja.',
        'not_tracked'       => 'Tuote :item ei seuraa varastoa.',
        'no_components'     => 'Tuotteella :item ei ole komponentteja, joista koota.',
    ],

];
