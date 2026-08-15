<?php

return [

    'stock'                 => 'Zaxira',
    'track_stock'           => 'Zaxirani hisobga olish',
    'stock_quantity'        => 'Ombordagi qoldiq',
    'opening_count'         => 'Boshlangʻich qoldiq',
    'adjust'                => 'Zaxirani tuzatish',
    'adjustment'            => 'Tuzatish',
    'set_to'                => 'Shu qiymatga oʻrnatish',
    'change_by'             => 'Shu miqdorga oʻzgartirish',
    'reason'                => 'Sabab',
    'movements'             => 'Zaxira harakati',
    'no_movements'          => 'Hozircha zaxira harakati yoʻq.',
    'via'                   => ':item orqali',
    'components'            => 'Komponentlar',
    'no_components'         => 'Hozircha komponentlar yoʻq.',
    'add_component'         => 'Komponent qoʻshish',
    'quantity_per_unit'     => 'Bir birlik uchun miqdor',
    'build'                 => 'Yigʻish',
    'assembly'              => 'Yigʻma mahsulot',

    'form_description' => [
        'stock'             => 'Omborda nechta birlik borligini hisobga oling. Hisob-fakturalar birliklarni ombordan chiqaradi, hisoblar esa kiritadi. Manfiy zaxiraga ruxsat beriladi.',
        'components'        => 'Ushbu mahsulot qaysi mahsulotlardan yigʻilishini koʻrsating. Agar ushbu mahsulot zaxirani hisobga olmasa, uni sotish komponentlarni ombordan chiqaradi. Agar hisobga olsa, komponentlardan tayyor birliklar yasash uchun Yigʻish tugmasidan foydalaning.',
        'build'             => 'Komponentlarni ombordan chiqaradi va tayyor birliklarni qoʻshadi. Manfiy miqdor birliklarni yana komponentlarga ajratadi.',
    ],

    'messages' => [
        'adjusted'          => ':item zaxirasi endi :quantity.',
        'built'             => ':item dan :quantity dona yigʻildi.',
    ],

    'errors' => [
        'component_is_self' => 'Mahsulot oʻzining komponenti boʻla olmaydi.',
        'component_is_kit'  => ':item allaqachon komponentlarga ega, shuning uchun uni komponent sifatida ishlatib boʻlmaydi.',
        'kit_as_component'  => ':item boshqa mahsulotning komponenti sifatida ishlatiladi, shuning uchun oʻz komponentlariga ega boʻla olmaydi.',
        'not_tracked'       => ':item zaxirani hisobga olmaydi.',
        'no_components'     => ':item da yigʻish uchun komponentlar yoʻq.',
    ],

];
