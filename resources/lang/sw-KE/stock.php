<?php

return [

    'stock'                 => 'Stoki',
    'track_stock'           => 'Fuatilia stoki',
    'stock_quantity'        => 'Stoki iliyopo',
    'opening_count'         => 'Hesabu ya kuanzia',
    'adjust'                => 'Rekebisha stoki',
    'adjustment'            => 'Marekebisho',
    'set_to'                => 'Weka kuwa',
    'change_by'             => 'Badilisha kwa',
    'reason'                => 'Sababu',
    'movements'             => 'Miamala ya stoki',
    'no_movements'          => 'Bado hakuna miamala ya stoki.',
    'via'                   => 'kupitia :item',
    'components'            => 'Vijenzi',
    'no_components'         => 'Bado hakuna vijenzi.',
    'add_component'         => 'Ongeza kijenzi',
    'quantity_per_unit'     => 'Idadi kwa kila kizio',
    'build'                 => 'Unganisha',
    'assembly'              => 'Bidhaa iliyounganishwa',

    'form_description' => [
        'stock'             => 'Fuatilia idadi ya yuniti zilizopo stoki. Ankara hutoa yuniti stoki na bili huziingiza. Stoki hasi inaruhusiwa.',
        'components'        => 'Orodhesha bidhaa ambazo bidhaa hii huunganishwa kutoka kwazo. Ikiwa bidhaa hii haifuatilii stoki, kuiuza hutoa vijenzi vyake stoki. Ikiwa inafuatilia, tumia Unganisha kugeuza vijenzi kuwa yuniti zilizokamilika.',
        'build'             => 'Hutoa vijenzi stoki na kuongeza yuniti zilizokamilika. Idadi hasi hutenganisha yuniti tena.',
    ],

    'messages' => [
        'adjusted'          => 'Stoki ya :item sasa ni :quantity.',
        'built'             => 'Zimeunganishwa :quantity za :item.',
    ],

    'errors' => [
        'component_is_self' => 'Bidhaa haiwezi kuwa kijenzi chake yenyewe.',
        'component_is_kit'  => ':item tayari ina vijenzi, kwa hivyo haiwezi kutumika kama kijenzi.',
        'kit_as_component'  => ':item inatumika kama kijenzi cha bidhaa nyingine, kwa hivyo haiwezi kuwa na vijenzi vyake.',
        'not_tracked'       => ':item haifuatilii stoki.',
        'no_components'     => ':item haina vijenzi vya kuunganisha.',
    ],

];
