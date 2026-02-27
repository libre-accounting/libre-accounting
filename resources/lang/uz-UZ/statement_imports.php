<?php

return [

    'form_description'  => 'Tranzaksiyalarni import qilish uchun CAMT.053 (ISO 20022 XML) bank koʻchirmasini yuklang. Qatorlar kitoblaringizga qoʻshilishidan oldin koʻrib chiqish uchun tayyorlanadi.',
    'account'           => 'Hisob',
    'file'              => 'Koʻchirma fayli',

    'statement'         => 'Koʻchirma',
    'period'            => 'Davr',
    'booked_at'         => 'Hisobga olingan',
    'value_date'        => 'Qiymat sanasi',
    'counterparty'      => 'Qarama-qarshi tomon',
    'remittance'        => 'Toʻlov maʼlumotlari',
    'reference'         => 'Havola',
    'lines'             => 'Qatorlar',
    'imported'          => 'Import qilingan',
    'total_lines'       => 'Jami qatorlar',
    'imported_lines'    => 'Import qilingan qatorlar',

    'statuses' => [
        'pending'   => 'Kutilmoqda',
        'imported'  => 'Import qilingan',
        'skipped'   => 'Oʻtkazib yuborilgan',
        'duplicate' => 'Dublikat',
        'reviewing' => 'Koʻrib chiqilmoqda',
        'completed' => 'Yakunlangan',
    ],

    'import_selected'   => 'Tanlanganlarni import qilish',
    'select_all'        => 'Hammasini belgilash',
    'set_category_all'  => 'Barcha tanlanganlar uchun kategoriyani belgilash',

    'messages' => [
        'staged'        => ':count qator tahlil qilindi va koʻrib chiqishga tayyor.',
        'committed'     => ':count tranzaksiya yaratildi.',
        'truncated'     => 'Koʻchirma katta edi: limitdan oshgan :count qator import qilinmadi.',
        'iban_mismatch' => 'Koʻchirmadagi IBAN tanlangan hisobga mos kelmaydi. Iltimos, hisobni qayta tekshiring.',
    ],

    'errors' => [
        'not_xml'           => 'Koʻchirma CAMT.053 XML fayli boʻlishi kerak.',
        'unreadable'        => 'Yuklangan faylni oʻqib boʻlmadi.',
        'already_imported'  => 'Aynan shu fayl allaqachon import qilingan.',
        'none_selected'     => 'Import qilish uchun kamida bitta qatorni tanlang.',
        'category_required' => 'Import qilishdan oldin har bir tanlangan qator uchun kategoriyani tanlang.',
    ],

];
