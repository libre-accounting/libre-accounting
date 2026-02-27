<?php

return [

    'form_description'  => 'Əməliyyatlarını idxal etmək üçün CAMT.053 (ISO 20022 XML) bank çıxarışını yükləyin. Sətirlər kitablarınıza heç nə əlavə edilməzdən əvvəl nəzərdən keçirilmək üçün hazırlanır.',
    'account'           => 'Hesab',
    'file'              => 'Çıxarış faylı',

    'statement'         => 'Çıxarış',
    'period'            => 'Dövr',
    'booked_at'         => 'Qeydə alınıb',
    'value_date'        => 'Dəyər tarixi',
    'counterparty'      => 'Qarşı tərəf',
    'remittance'        => 'Ödəniş məlumatı',
    'reference'         => 'İstinad',
    'lines'             => 'Sətirlər',
    'imported'          => 'İdxal edilib',
    'total_lines'       => 'Ümumi sətirlər',
    'imported_lines'    => 'İdxal edilmiş sətirlər',

    'statuses' => [
        'pending'   => 'Gözləyir',
        'imported'  => 'İdxal edilib',
        'skipped'   => 'Ötürülüb',
        'duplicate' => 'Dublikat',
        'reviewing' => 'Nəzərdən keçirilir',
        'completed' => 'Tamamlanıb',
    ],

    'import_selected'   => 'Seçilmişləri idxal et',
    'select_all'        => 'Hamısını seç',
    'set_category_all'  => 'Bütün seçilmişlər üçün kateqoriya təyin et',

    'messages' => [
        'staged'        => ':count sətir təhlil edildi və nəzərdən keçirilməyə hazırdır.',
        'committed'     => ':count əməliyyat yaradıldı.',
        'truncated'     => 'Çıxarış böyük idi: limitdən kənar :count sətir idxal edilmədi.',
        'iban_mismatch' => 'Çıxarışın IBAN-ı seçilmiş hesabla uyğun gəlmir. Zəhmət olmasa hesabı bir daha yoxlayın.',
    ],

    'errors' => [
        'not_xml'           => 'Çıxarış CAMT.053 XML faylı olmalıdır.',
        'unreadable'        => 'Yüklənmiş fayl oxuna bilmədi.',
        'already_imported'  => 'Bu eyni fayl artıq idxal edilib.',
        'none_selected'     => 'İdxal etmək üçün ən azı bir sətir seçin.',
        'category_required' => 'İdxal etməzdən əvvəl hər seçilmiş sətir üçün kateqoriya seçin.',
    ],

];
