<?php

return [

    'stock'                 => 'Anbar',
    'track_stock'           => 'Anbar qalığını izlə',
    'untrack_stock'         => 'Anbar qalığını izləməyi dayandır',
    'stock_quantity'        => 'Anbar qalığı',
    'opening_count'         => 'Açılış qalığı',
    'adjust'                => 'Anbar qalığını düzəlt',
    'adjustment'            => 'Düzəliş',
    'set_to'                => 'Təyin et',
    'change_by'             => 'Bu qədər dəyiş',
    'reason'                => 'Səbəb',
    'movements'             => 'Anbar hərəkətləri',
    'no_movements'          => 'Hələ anbar hərəkəti yoxdur.',
    'via'                   => ':item vasitəsilə',
    'components'            => 'Komponentlər',
    'no_components'         => 'Hələ komponent yoxdur.',
    'add_component'         => 'Komponent əlavə et',
    'quantity_per_unit'     => 'Vahid üzrə miqdar',
    'build'                 => 'Komplektləşdir',
    'assembly'              => 'Komplektləşdirmə',

    'form_description' => [
        'stock'             => 'Anbarda neçə vahid olduğunu izləyin. Gəlir fakturaları vahidləri anbardan çıxarır, xərc fakturaları isə anbara daxil edir. Mənfi anbar qalığına icazə verilir.',
        'components'        => 'Bu məhsulun hansı məhsullardan yığıldığını sadalayın. Bu məhsul anbar qalığını izləmirsə, onun satışı komponentləri anbardan çıxarır. İzləyirsə, komponentləri hazır vahidlərə çevirmək üçün «Komplektləşdir» düyməsindən istifadə edin.',
        'build'             => 'Komponentləri anbardan çıxarır və hazır vahidləri əlavə edir. Mənfi miqdar vahidləri yenidən komponentlərə ayırır.',
    ],

    'bulk' => [
        'track'             => 'Seçilmiş :type üçün <b>anbar qalığını izləmək</b> istədiyinizə əminsiniz?',
        'untrack'           => 'Seçilmiş :type üçün <b>anbar qalığının izlənməsini dayandırmaq</b> istədiyinizə əminsiniz?',
    ],

    'messages' => [
        'adjusted'          => ':item üçün anbar qalığı indi :quantity təşkil edir.',
        'built'             => ':item məhsulundan :quantity ədəd komplektləşdirildi.',
        'tracked'           => 'Seçilmiş məhsullar üçün anbar qalığının izlənməsi aktivləşdirildi.',
        'untracked'         => 'Seçilmiş məhsullar üçün anbar qalığının izlənməsi dayandırıldı.',
    ],

    'errors' => [
        'component_is_self' => 'Məhsul özünün komponenti ola bilməz.',
        'component_is_kit'  => ':item artıq komponentlərə malikdir, buna görə də komponent kimi istifadə edilə bilməz.',
        'kit_as_component'  => ':item başqa bir məhsulun komponenti kimi istifadə olunur, buna görə də özünün komponentləri ola bilməz.',
        'not_tracked'       => ':item anbar qalığını izləmir.',
        'no_components'     => ':item məhsulunun komplektləşdirmə üçün komponenti yoxdur.',
        'not_product'       => ':item xidmətdir, buna görə də anbar qalığını izləyə bilməz.',
    ],

];
