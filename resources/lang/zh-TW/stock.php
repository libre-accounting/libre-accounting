<?php

return [

    'stock'                 => '庫存',
    'track_stock'           => '追蹤庫存',
    'untrack_stock'         => '停止追蹤庫存',
    'stock_quantity'        => '現有庫存',
    'opening_count'         => '期初數量',
    'adjust'                => '調整庫存',
    'adjustment'            => '調整',
    'set_to'                => '設為',
    'change_by'             => '增減',
    'reason'                => '原因',
    'movements'             => '庫存異動',
    'no_movements'          => '尚無庫存異動。',
    'via'                   => '透過 :item',
    'components'            => '組件',
    'no_components'         => '尚無組件。',
    'add_component'         => '新增組件',
    'quantity_per_unit'     => '每單位數量',
    'build'                 => '組裝',
    'assembly'              => '組裝品',

    'form_description' => [
        'stock'             => '追蹤庫存中有多少單位。發票會減少庫存，帳單會增加庫存。允許負庫存。',
        'components'        => '列出組成此產品的各個產品。若此產品不追蹤庫存，銷售時會從庫存扣除其組件。若有追蹤庫存，請使用「組裝」將組件轉為成品。',
        'build'             => '從庫存扣除組件並加入成品。負數數量會將成品重新拆回組件。',
    ],

    'bulk' => [
        'track'             => '你確定要為選定的 :type <b>追蹤庫存</b>嗎？',
        'untrack'           => '你確定要為選定的 :type <b>停止追蹤庫存</b>嗎？',
    ],

    'messages' => [
        'adjusted'          => ':item 的庫存現為 :quantity。',
        'built'             => '已組裝 :quantity 個 :item。',
        'tracked'           => '已為選定的產品開啟庫存追蹤。',
        'untracked'         => '已為選定的產品關閉庫存追蹤。',
    ],

    'errors' => [
        'component_is_self' => '產品不能作為自身的組件。',
        'component_is_kit'  => ':item 已有組件，因此不能用作組件。',
        'kit_as_component'  => ':item 已被用作其他產品的組件，因此不能擁有自己的組件。',
        'not_tracked'       => ':item 不追蹤庫存。',
        'no_components'     => ':item 沒有可用於組裝的組件。',
        'not_product'       => ':item 是服務，因此不能追蹤庫存。',
    ],

];
