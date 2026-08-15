<?php

return [

    'stock'                 => '재고',
    'track_stock'           => '재고 추적',
    'stock_quantity'        => '현재 재고',
    'opening_count'         => '기초 재고',
    'adjust'                => '재고 조정',
    'adjustment'            => '조정',
    'set_to'                => '다음으로 설정',
    'change_by'             => '다음만큼 변경',
    'reason'                => '사유',
    'movements'             => '재고 변동',
    'no_movements'          => '아직 재고 변동이 없습니다.',
    'via'                   => ':item 경유',
    'components'            => '구성품',
    'no_components'         => '아직 구성품이 없습니다.',
    'add_component'         => '구성품 추가',
    'quantity_per_unit'     => '단위당 수량',
    'build'                 => '조립하기',
    'assembly'              => '조립',

    'form_description' => [
        'stock'             => '재고 수량을 추적합니다. 청구서는 재고를 줄이고 계산서는 재고를 늘립니다. 마이너스 재고도 허용됩니다.',
        'components'        => '이 항목을 구성하는 항목을 나열하세요. 이 항목이 재고를 추적하지 않으면 판매 시 구성품의 재고가 차감됩니다. 재고를 추적하는 경우 조립하기를 사용해 구성품을 완성품으로 전환하세요.',
        'build'             => '구성품의 재고를 차감하고 완성품을 재고에 추가합니다. 수량을 마이너스로 입력하면 완성품을 다시 구성품으로 분해합니다.',
    ],

    'messages' => [
        'adjusted'          => ':item의 현재 재고는 :quantity입니다.',
        'built'             => ':item :quantity개를 조립했습니다.',
    ],

    'errors' => [
        'component_is_self' => '항목은 자기 자신의 구성품이 될 수 없습니다.',
        'component_is_kit'  => ':item에는 이미 구성품이 있으므로 구성품으로 사용할 수 없습니다.',
        'kit_as_component'  => ':item은(는) 다른 항목의 구성품으로 사용되고 있으므로 자체 구성품을 가질 수 없습니다.',
        'not_tracked'       => ':item은(는) 재고를 추적하지 않습니다.',
        'no_components'     => ':item에는 조립할 구성품이 없습니다.',
    ],

];
