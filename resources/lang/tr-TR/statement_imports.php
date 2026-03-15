<?php

return [

    'form_description'  => 'İşlemlerini içe aktarmak için bir CAMT.053 (ISO 20022 XML) banka ekstresi yükleyin. Satırlar, defterlerinize herhangi bir kayıt eklenmeden önce inceleme için hazırlanır.',
    'account'           => 'Hesap',
    'file'              => 'Ekstre dosyası',

    'statement'         => 'Ekstre',
    'period'            => 'Dönem',
    'booked_at'         => 'Kaydedildi',
    'value_date'        => 'Valör tarihi',
    'counterparty'      => 'Karşı taraf',
    'remittance'        => 'Havale bilgileri',
    'reference'         => 'Referans',
    'lines'             => 'Satırlar',
    'imported'          => 'İçe aktarıldı',
    'total_lines'       => 'Toplam satır',
    'imported_lines'    => 'İçe aktarılan satırlar',

    'statuses' => [
        'pending'   => 'Beklemede',
        'imported'  => 'İçe aktarıldı',
        'skipped'   => 'Atlandı',
        'duplicate' => 'Yinelenen',
        'reviewing' => 'İnceleniyor',
        'completed' => 'Tamamlandı',
    ],

    'import_selected'   => 'Seçilenleri içe aktar',
    'select_all'        => 'Tümünü seç',
    'set_category_all'  => 'Seçilen tümü için kategori ayarla',

    'messages' => [
        'staged'        => ':count satır ayrıştırıldı ve incelemeye hazır.',
        'committed'     => ':count işlem oluşturuldu.',
        'truncated'     => 'Ekstre büyüktü: sınırın ötesindeki :count satır içe aktarılmadı.',
        'iban_mismatch' => 'Ekstredeki IBAN seçilen hesapla eşleşmiyor. Lütfen hesabı tekrar kontrol edin.',
    ],

    'errors' => [
        'not_xml'           => 'Ekstre bir CAMT.053 XML dosyası olmalıdır.',
        'unreadable'        => 'Yüklenen dosya okunamadı.',
        'already_imported'  => 'Bu dosya zaten içe aktarılmış.',
        'staging_failed'    => 'Ekstre inceleme için hazırlanamadı. Lütfen tekrar deneyin.',
        'none_selected'     => 'İçe aktarmak için en az bir satır seçin.',
        'category_required' => 'İçe aktarmadan önce seçilen her satır için bir kategori seçin.',
    ],

];
