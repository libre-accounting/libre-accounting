<?php

return [

    'stock'                 => 'Inventario',
    'track_stock'           => 'Controlar inventario',
    'stock_quantity'        => 'Cantidad disponible',
    'opening_count'         => 'Inventario inicial',
    'adjust'                => 'Ajustar inventario',
    'adjustment'            => 'Ajuste',
    'set_to'                => 'Establecer en',
    'change_by'             => 'Modificar en',
    'reason'                => 'Motivo',
    'movements'             => 'Movimientos de inventario',
    'no_movements'          => 'Aún no hay movimientos de inventario.',
    'via'                   => 'a través de :item',
    'components'            => 'Componentes',
    'no_components'         => 'Aún no hay componentes.',
    'add_component'         => 'Agregar componente',
    'quantity_per_unit'     => 'Cantidad por unidad',
    'build'                 => 'Ensamblar',
    'assembly'              => 'Ensamble',

    'form_description' => [
        'stock'             => 'Controle cuántas unidades hay en inventario. Las facturas descuentan unidades y los recibos las agregan. Se permite inventario negativo.',
        'components'        => 'Indique los artículos con los que se ensambla este artículo. Si este artículo no controla inventario, al venderlo se descuentan los componentes del inventario. Si lo controla, use «Ensamblar» para convertir los componentes en unidades terminadas.',
        'build'             => 'Descuenta los componentes del inventario y agrega las unidades terminadas. Una cantidad negativa vuelve a desensamblar las unidades.',
    ],

    'messages' => [
        'adjusted'          => 'El inventario de :item ahora es :quantity.',
        'built'             => 'Se ensamblaron :quantity unidades de :item.',
    ],

    'errors' => [
        'component_is_self' => 'Un artículo no puede ser componente de sí mismo.',
        'component_is_kit'  => ':item ya tiene componentes, por lo que no puede usarse como componente.',
        'kit_as_component'  => ':item se usa como componente de otro artículo, por lo que no puede tener componentes propios.',
        'not_tracked'       => ':item no controla inventario.',
        'no_components'     => ':item no tiene componentes con los que ensamblarlo.',
    ],

];
