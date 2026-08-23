<?php

return [

    'stock'                 => '库存',
    'track_stock'           => '跟踪库存',
    'untrack_stock'         => '停止跟踪库存',
    'stock_quantity'        => '现有库存',
    'opening_count'         => '期初数量',
    'adjust'                => '调整库存',
    'adjustment'            => '调整',
    'set_to'                => '设为',
    'change_by'             => '增减',
    'reason'                => '原因',
    'movements'             => '库存变动',
    'no_movements'          => '暂无库存变动。',
    'via'                   => '通过 :item',
    'components'            => '组件',
    'no_components'         => '暂无组件。',
    'add_component'         => '添加组件',
    'quantity_per_unit'     => '每单位数量',
    'build'                 => '组装',
    'assembly'              => '组装件',

    'form_description' => [
        'stock'             => '跟踪库存中有多少单位。发票会减少库存，账单会增加库存。允许负库存。',
        'components'        => '列出组成此产品的各个产品。如果此产品不跟踪库存，销售它时会从库存中扣减其组件。如果跟踪库存，请使用“组装”将组件转为成品。',
        'build'             => '从库存中扣减组件并加入成品。负数数量会将成品重新拆回组件。',
    ],

    'bulk' => [
        'track'             => '你确定要为选定的 :type <b>跟踪库存</b>吗？',
        'untrack'           => '你确定要为选定的 :type <b>停止跟踪库存</b>吗？',
    ],

    'messages' => [
        'adjusted'          => ':item 的库存现为 :quantity。',
        'built'             => '已组装 :quantity 个 :item。',
        'tracked'           => '已为选定的产品开启库存跟踪。',
        'untracked'         => '已为选定的产品关闭库存跟踪。',
    ],

    'errors' => [
        'component_is_self' => '产品不能作为自身的组件。',
        'component_is_kit'  => ':item 已有组件，因此不能用作组件。',
        'kit_as_component'  => ':item 已被用作其他产品的组件，因此不能拥有自己的组件。',
        'not_tracked'       => ':item 不跟踪库存。',
        'no_components'     => ':item 没有可用于组装的组件。',
        'not_product'       => ':item 是服务，因此不能跟踪库存。',
    ],

];
