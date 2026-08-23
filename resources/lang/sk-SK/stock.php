<?php

return [

    'stock'                 => 'Sklad',
    'track_stock'           => 'Sledovať zásoby',
    'untrack_stock'         => 'Prestať sledovať zásoby',
    'stock_quantity'        => 'Stav zásob',
    'opening_count'         => 'Počiatočný stav',
    'adjust'                => 'Upraviť zásoby',
    'adjustment'            => 'Úprava',
    'set_to'                => 'Nastaviť na',
    'change_by'             => 'Zmeniť o',
    'reason'                => 'Dôvod',
    'movements'             => 'Skladové pohyby',
    'no_movements'          => 'Zatiaľ žiadne skladové pohyby.',
    'via'                   => 'cez :item',
    'components'            => 'Komponenty',
    'no_components'         => 'Zatiaľ žiadne komponenty.',
    'add_component'         => 'Pridať komponent',
    'quantity_per_unit'     => 'Množstvo na jednotku',
    'build'                 => 'Zostaviť',
    'assembly'              => 'Zostavenie',

    'form_description' => [
        'stock'             => 'Sledujte, koľko jednotiek je na sklade. Vystavené faktúry jednotky zo skladu odpisujú a prijaté faktúry ich naskladňujú. Záporný stav zásob je povolený.',
        'components'        => 'Uveďte položky, z ktorých sa táto položka skladá. Ak táto položka nesleduje zásoby, jej predajom sa zo skladu odpíšu komponenty. Ak ich sleduje, použite Zostaviť na premenu komponentov na hotové jednotky.',
        'build'             => 'Odpíše komponenty zo skladu a naskladní hotové jednotky. Záporné množstvo jednotky opäť rozloží na komponenty.',
    ],

    'bulk' => [
        'track'             => 'Ste si istý že chcete <b>sledovať zásoby</b> pre vybrané :type?',
        'untrack'           => 'Ste si istý že chcete <b>prestať sledovať zásoby</b> pre vybrané :type?',
    ],

    'messages' => [
        'adjusted'          => 'Stav zásob položky :item je teraz :quantity.',
        'built'             => 'Zostavených :quantity ks položky :item.',
        'tracked'           => 'Sledovanie zásob bolo zapnuté pre vybrané položky.',
        'untracked'         => 'Sledovanie zásob bolo vypnuté pre vybrané položky.',
    ],

    'errors' => [
        'component_is_self' => 'Položka nemôže byť komponentom samej seba.',
        'component_is_kit'  => 'Položka :item už má komponenty, preto ju nemožno použiť ako komponent.',
        'kit_as_component'  => 'Položka :item sa používa ako komponent inej položky, preto nemôže mať vlastné komponenty.',
        'not_tracked'       => 'Položka :item nesleduje zásoby.',
        'no_components'     => 'Položka :item nemá komponenty, z ktorých by sa dala zostaviť.',
        'not_product'       => 'Položka :item je služba, preto nemôže sledovať zásoby.',
    ],

];
