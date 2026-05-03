<?php

return [

    'export'            => 'Eksport / Zaxira nusxa',
    'import'            => 'Zaxira nusxani import qilish',
    'restoring'         => 'Tiklanmoqda…',
    'switch_to_company' => 'Kompaniyaga o\'tish',

    'import_title'       => 'Libre Accounting zaxira nusxasidan tiklash',
    'import_description' => 'Ushbu nusxada kompaniyani qayta yaratish uchun Libre Accounting\'dan eksport qilingan zaxira arxivini (.zip) yuklang.',
    'import_action'      => 'Zaxira nusxadan tiklash',

    'messages' => [
        'export_started'    => 'Zaxira nusxa yaratish boshlandi. Yuklab olish tayyor bo\'lganda sizga xabar beriladi.',
        'exporting'         => 'Kompaniya zaxira nusxasi yaratilmoqda…',
        'export_completed'  => 'Kompaniya zaxira nusxasi tayyor.',
        'import_started'    => 'Tiklash boshlandi. Katta zaxira nusxalar uchun bu biroz vaqt olishi mumkin.',
        'importing'         => 'Kompaniya tiklanmoqda…',
        'import_completed'  => 'Kompaniya muvaffaqiyatli tiklandi.',
        'failed'            => 'Nimadir xato ketdi.',
    ],

    'warnings' => [
        'title'                 => 'Tiklash bir nechta izohlar bilan yakunlandi:',
        'disabled_modules'      => 'Ushbu modullar zaxira nusxada ishlatilgan, ammo bu yerda o\'rnatilmagan, shuning uchun ular o\'chirilgan holda qoldirildi: :modules.',
        'skipped_reports'       => ':count ta hisobot o\'tkazib yuborildi, chunki ularning hisobot turi ushbu nusxada o\'rnatilmagan.',
        'unbundled_media'       => ':count ta fayl masofaviy xotirada saqlangan va arxivga qo\'shilmagan; ularni qo\'lda qayta yuklang.',
        'missing_currency_refs' => ':count ta yozuv zaxira nusxada mavjud bo\'lmagan valyutaga murojaat qildi va standart valyutaga qaytarildi.',
    ],

    'errors' => [
        'invalid_format' => 'Yuklangan fayl yaroqli Libre Accounting kompaniya zaxira nusxasi emas.',
        'newer_version'  => 'Ushbu zaxira nusxa Libre Accounting\'ning yangiroq versiyasi tomonidan yaratilgan. Tiklashdan oldin ushbu nusxani yangilang.',
    ],

];
