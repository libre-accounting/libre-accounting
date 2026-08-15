<?php

return [

    'stock'                 => '在庫',
    'track_stock'           => '在庫を管理する',
    'stock_quantity'        => '在庫数',
    'opening_count'         => '初期在庫数',
    'adjust'                => '在庫を調整',
    'adjustment'            => '在庫調整',
    'set_to'                => '指定数に設定',
    'change_by'             => '増減数を指定',
    'reason'                => '理由',
    'movements'             => '在庫変動',
    'no_movements'          => '在庫変動はまだありません。',
    'via'                   => ':item 経由',
    'components'            => '構成品',
    'no_components'         => '構成品はまだありません。',
    'add_component'         => '構成品を追加',
    'quantity_per_unit'     => '1単位あたりの数量',
    'build'                 => '組み立てる',
    'assembly'              => '組み立て',

    'form_description' => [
        'stock'             => '在庫数を管理します。売上の請求書で在庫が減り、仕入の請求書で在庫が増えます。マイナス在庫も許可されます。',
        'components'        => 'この商品を構成する商品を一覧にします。この商品が在庫を管理しない場合、販売時に構成品の在庫が減ります。在庫を管理する場合は、「組み立てる」で構成品を完成品に変換します。',
        'build'             => '構成品の在庫を減らし、完成品を在庫に加えます。マイナスの数量を指定すると、完成品を再び構成品に分解します。',
    ],

    'messages' => [
        'adjusted'          => ':item の在庫数は :quantity になりました。',
        'built'             => ':item を :quantity 個組み立てました。',
    ],

    'errors' => [
        'component_is_self' => '商品を自身の構成品にすることはできません。',
        'component_is_kit'  => ':item には既に構成品があるため、構成品として使用できません。',
        'kit_as_component'  => ':item は他の商品の構成品として使用されているため、独自の構成品を持つことはできません。',
        'not_tracked'       => ':item は在庫を管理していません。',
        'no_components'     => ':item には組み立てに使う構成品がありません。',
    ],

];
