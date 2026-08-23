<?php

return [

    'stock'                 => 'Estoque',
    'track_stock'           => 'Controlar estoque',
    'untrack_stock'         => 'Parar de controlar estoque',
    'stock_quantity'        => 'Saldo em estoque',
    'opening_count'         => 'Estoque inicial',
    'adjust'                => 'Ajustar estoque',
    'adjustment'            => 'Ajuste',
    'set_to'                => 'Definir como',
    'change_by'             => 'Alterar em',
    'reason'                => 'Motivo',
    'movements'             => 'Movimentações de estoque',
    'no_movements'          => 'Nenhuma movimentação de estoque ainda.',
    'via'                   => 'via :item',
    'components'            => 'Componentes',
    'no_components'         => 'Nenhum componente ainda.',
    'add_component'         => 'Adicionar componente',
    'quantity_per_unit'     => 'Quantidade por unidade',
    'build'                 => 'Montar',
    'assembly'              => 'Montagem',

    'form_description' => [
        'stock'             => 'Controle quantas unidades há em estoque. Faturas dão saída nas unidades e contas dão entrada. Estoque negativo é permitido.',
        'components'        => 'Liste os itens que compõem este item. Se este item não controla estoque, vendê-lo dá saída nos componentes. Se controla, use Montar para transformar componentes em unidades acabadas.',
        'build'             => 'Dá saída nos componentes e dá entrada nas unidades acabadas. Uma quantidade negativa desmonta as unidades novamente.',
    ],

    'bulk' => [
        'track'             => 'Tem certeza de que deseja <b>controlar o estoque</b> dos :type selecionados?',
        'untrack'           => 'Tem certeza de que deseja <b>parar de controlar o estoque</b> dos :type selecionados?',
    ],

    'messages' => [
        'adjusted'          => 'O estoque de :item agora é :quantity.',
        'built'             => 'Montagem concluída: :quantity de :item.',
        'tracked'           => 'Controle de estoque ativado para os itens selecionados.',
        'untracked'         => 'Controle de estoque desativado para os itens selecionados.',
    ],

    'errors' => [
        'component_is_self' => 'Um item não pode ser componente de si mesmo.',
        'component_is_kit'  => ':item já tem componentes, portanto não pode ser usado como componente.',
        'kit_as_component'  => ':item é usado como componente de outro item, portanto não pode ter componentes próprios.',
        'not_tracked'       => ':item não controla estoque.',
        'no_components'     => ':item não tem componentes para montar.',
        'not_product'       => ':item é um serviço, portanto não pode controlar estoque.',
    ],

];
