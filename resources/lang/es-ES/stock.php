<?php

return [

    'stock'                 => 'Stock',
    'track_stock'           => 'Controlar stock',
    'untrack_stock'         => 'Dejar de controlar stock',
    'stock_quantity'        => 'Stock disponible',
    'opening_count'         => 'Stock inicial',
    'adjust'                => 'Ajustar stock',
    'adjustment'            => 'Ajuste',
    'set_to'                => 'Establecer en',
    'change_by'             => 'Modificar en',
    'reason'                => 'Motivo',
    'movements'             => 'Movimientos de stock',
    'no_movements'          => 'Todavía no hay movimientos de stock.',
    'via'                   => 'a través de :item',
    'components'            => 'Componentes',
    'no_components'         => 'Todavía no hay componentes.',
    'add_component'         => 'Añadir componente',
    'quantity_per_unit'     => 'Cantidad por unidad',
    'build'                 => 'Montar',
    'assembly'              => 'Montaje',

    'form_description' => [
        'stock'             => 'Controle cuántas unidades hay en stock. Las facturas descuentan unidades y los recibos las añaden. Se permite stock negativo.',
        'components'        => 'Indique los artículos con los que se monta este artículo. Si este artículo no controla stock, al venderlo se descuentan los componentes del stock. Si lo controla, use «Montar» para convertir los componentes en unidades terminadas.',
        'build'             => 'Descuenta los componentes del stock y añade las unidades terminadas. Una cantidad negativa vuelve a desmontar las unidades.',
    ],

    'bulk' => [
        'track'             => '¿Está seguro que desea <b>controlar el stock</b> de los :type seleccionados?',
        'untrack'           => '¿Está seguro que desea <b>dejar de controlar el stock</b> de los :type seleccionados?',
    ],

    'messages' => [
        'adjusted'          => 'El stock de :item ahora es :quantity.',
        'built'             => 'Se han montado :quantity unidades de :item.',
        'tracked'           => 'Se ha activado el control de stock de los artículos seleccionados.',
        'untracked'         => 'Se ha desactivado el control de stock de los artículos seleccionados.',
    ],

    'errors' => [
        'component_is_self' => 'Un artículo no puede ser componente de sí mismo.',
        'component_is_kit'  => ':item ya tiene componentes, por lo que no puede usarse como componente.',
        'kit_as_component'  => ':item se usa como componente de otro artículo, por lo que no puede tener componentes propios.',
        'not_tracked'       => ':item no controla stock.',
        'no_components'     => ':item no tiene componentes con los que montarlo.',
        'not_product'       => ':item es un servicio, por lo que no puede controlar stock.',
    ],

];
