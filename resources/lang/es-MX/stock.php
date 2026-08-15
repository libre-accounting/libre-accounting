<?php

return [

    'stock'                 => 'Existencias',
    'track_stock'           => 'Controlar existencias',
    'stock_quantity'        => 'Existencias disponibles',
    'opening_count'         => 'Conteo inicial',
    'adjust'                => 'Ajustar existencias',
    'adjustment'            => 'Ajuste',
    'set_to'                => 'Establecer en',
    'change_by'             => 'Cambiar en',
    'reason'                => 'Motivo',
    'movements'             => 'Movimientos de existencias',
    'no_movements'          => 'Aún no hay movimientos de existencias.',
    'via'                   => 'vía :item',
    'components'            => 'Componentes',
    'no_components'         => 'Aún no hay componentes.',
    'add_component'         => 'Agregar componente',
    'quantity_per_unit'     => 'Cantidad por unidad',
    'build'                 => 'Ensamblar',
    'assembly'              => 'Ensamble',

    'form_description' => [
        'stock'             => 'Controle cuántas unidades hay en existencia. Las facturas restan unidades y los recibos las agregan. Se permiten existencias negativas.',
        'components'        => 'Enumere los artículos con los que se ensambla este artículo. Si este artículo no controla existencias, al venderlo se descuentan los componentes de las existencias. Si las controla, use Ensamblar para convertir los componentes en unidades terminadas.',
        'build'             => 'Descuenta los componentes de las existencias y agrega las unidades terminadas. Una cantidad negativa vuelve a desarmar las unidades.',
    ],

    'messages' => [
        'adjusted'          => 'Las existencias de :item ahora son :quantity.',
        'built'             => 'Se ensamblaron :quantity unidades de :item.',
    ],

    'errors' => [
        'component_is_self' => 'Un artículo no puede ser componente de sí mismo.',
        'component_is_kit'  => ':item ya tiene componentes, por lo que no puede usarse como componente.',
        'kit_as_component'  => ':item se usa como componente de otro artículo, por lo que no puede tener componentes propios.',
        'not_tracked'       => ':item no controla existencias.',
        'no_components'     => ':item no tiene componentes con los que ensamblarlo.',
    ],

];
