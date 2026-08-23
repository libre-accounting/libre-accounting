<?php

return [

    'stock'                 => 'Sklad',
    'track_stock'           => 'Sledovat stav skladu',
    'untrack_stock'         => 'Přestat sledovat stav skladu',
    'stock_quantity'        => 'Stav skladu',
    'opening_count'         => 'Počáteční stav',
    'adjust'                => 'Upravit stav skladu',
    'adjustment'            => 'Úprava',
    'set_to'                => 'Nastavit na',
    'change_by'             => 'Změnit o',
    'reason'                => 'Důvod',
    'movements'             => 'Skladové pohyby',
    'no_movements'          => 'Zatím žádné skladové pohyby.',
    'via'                   => 'přes :item',
    'components'            => 'Komponenty',
    'no_components'         => 'Zatím žádné komponenty.',
    'add_component'         => 'Přidat komponentu',
    'quantity_per_unit'     => 'Množství na jednotku',
    'build'                 => 'Sestavit',
    'assembly'              => 'Sestavení',

    'form_description' => [
        'stock'             => 'Sledujte, kolik jednotek je skladem. Vydané faktury jednotky ze skladu odebírají, přijaté faktury je naskladňují. Záporný stav skladu je povolen.',
        'components'        => 'Uveďte položky, ze kterých se tato položka sestavuje. Pokud tato položka nesleduje stav skladu, jejím prodejem se komponenty odeberou ze skladu. Pokud ho sleduje, použijte „Sestavit“ a přeměňte komponenty na hotové jednotky.',
        'build'             => 'Odebere komponenty ze skladu a přidá hotové jednotky. Záporné množství jednotky opět rozebere.',
    ],

    'bulk' => [
        'track'             => 'Jste si jisti, že chcete <b>sledovat stav skladu</b> pro vybrané :type?',
        'untrack'           => 'Jste si jisti, že chcete <b>přestat sledovat stav skladu</b> pro vybrané :type?',
    ],

    'messages' => [
        'adjusted'          => 'Stav skladu položky :item je nyní :quantity.',
        'built'             => 'Sestaveno :quantity ks položky :item.',
        'tracked'           => 'Sledování stavu skladu bylo pro vybrané položky zapnuto.',
        'untracked'         => 'Sledování stavu skladu bylo pro vybrané položky vypnuto.',
    ],

    'errors' => [
        'component_is_self' => 'Položka nemůže být komponentou sebe sama.',
        'component_is_kit'  => ':item již má komponenty, proto ji nelze použít jako komponentu.',
        'kit_as_component'  => ':item se používá jako komponenta jiné položky, proto nemůže mít vlastní komponenty.',
        'not_tracked'       => ':item nesleduje stav skladu.',
        'no_components'     => ':item nemá žádné komponenty, ze kterých by šla sestavit.',
        'not_product'       => ':item je služba, proto nemůže sledovat stav skladu.',
    ],

];
