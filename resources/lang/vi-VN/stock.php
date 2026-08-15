<?php

return [

    'stock'                 => 'Tồn kho',
    'track_stock'           => 'Theo dõi tồn kho',
    'stock_quantity'        => 'Số lượng tồn kho',
    'opening_count'         => 'Tồn đầu kỳ',
    'adjust'                => 'Điều chỉnh tồn kho',
    'adjustment'            => 'Điều chỉnh',
    'set_to'                => 'Đặt thành',
    'change_by'             => 'Tăng/giảm',
    'reason'                => 'Lý do',
    'movements'             => 'Biến động tồn kho',
    'no_movements'          => 'Chưa có biến động tồn kho.',
    'via'                   => 'qua :item',
    'components'            => 'Thành phần',
    'no_components'         => 'Chưa có thành phần nào.',
    'add_component'         => 'Thêm thành phần',
    'quantity_per_unit'     => 'Số lượng trên mỗi đơn vị',
    'build'                 => 'Lắp ráp',
    'assembly'              => 'Sản phẩm lắp ráp',

    'form_description' => [
        'stock'             => 'Theo dõi số lượng đơn vị đang có trong kho. Hoá đơn bán hàng xuất hàng khỏi kho và hoá đơn mua hàng nhập hàng vào kho. Cho phép tồn kho âm.',
        'components'        => 'Liệt kê các mục dùng để lắp ráp nên mục này. Nếu mục này không theo dõi tồn kho, việc bán nó sẽ xuất các thành phần khỏi kho. Nếu có theo dõi, hãy dùng Lắp ráp để chuyển các thành phần thành thành phẩm.',
        'build'             => 'Xuất các thành phần khỏi kho và nhập thành phẩm vào kho. Số lượng âm sẽ tháo rời thành phẩm trở lại thành các thành phần.',
    ],

    'messages' => [
        'adjusted'          => 'Tồn kho của :item hiện là :quantity.',
        'built'             => 'Đã lắp ráp :quantity :item.',
    ],

    'errors' => [
        'component_is_self' => 'Một mục không thể là thành phần của chính nó.',
        'component_is_kit'  => ':item đã có thành phần nên không thể dùng làm thành phần.',
        'kit_as_component'  => ':item đang được dùng làm thành phần của mục khác nên không thể có thành phần riêng.',
        'not_tracked'       => ':item không theo dõi tồn kho.',
        'no_components'     => ':item không có thành phần nào để lắp ráp.',
    ],

];
