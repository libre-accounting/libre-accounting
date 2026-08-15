<?php

return [

    'stock'                 => 'Stock',
    'track_stock'           => 'Controlar stock',
    'stock_quantity'        => 'Stock atual',
    'opening_count'         => 'Stock inicial',
    'adjust'                => 'Ajustar stock',
    'adjustment'            => 'Ajuste',
    'set_to'                => 'Definir como',
    'change_by'             => 'Alterar em',
    'reason'                => 'Motivo',
    'movements'             => 'Movimentos de stock',
    'no_movements'          => 'Ainda não há movimentos de stock.',
    'via'                   => 'via :item',
    'components'            => 'Componentes',
    'no_components'         => 'Ainda não há componentes.',
    'add_component'         => 'Adicionar componente',
    'quantity_per_unit'     => 'Quantidade por unidade',
    'build'                 => 'Montar',
    'assembly'              => 'Montagem',

    'form_description' => [
        'stock'             => 'Controle quantas unidades existem em stock. As faturas dão saída às unidades e as contas dão entrada. É permitido stock negativo.',
        'components'        => 'Indique os itens que compõem este item. Se este item não controlar stock, a sua venda dá saída aos componentes. Se controlar, utilize Montar para transformar os componentes em unidades acabadas.',
        'build'             => 'Dá saída aos componentes e dá entrada às unidades acabadas. Uma quantidade negativa volta a desmontar as unidades.',
    ],

    'messages' => [
        'adjusted'          => 'O stock de :item é agora :quantity.',
        'built'             => 'Montagem concluída: :quantity de :item.',
    ],

    'errors' => [
        'component_is_self' => 'Um item não pode ser componente de si próprio.',
        'component_is_kit'  => ':item já tem componentes, pelo que não pode ser utilizado como componente.',
        'kit_as_component'  => ':item é utilizado como componente de outro item, pelo que não pode ter componentes próprios.',
        'not_tracked'       => ':item não controla stock.',
        'no_components'     => ':item não tem componentes para montar.',
    ],

];
