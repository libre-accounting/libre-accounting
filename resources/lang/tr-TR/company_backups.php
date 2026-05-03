<?php

return [

    'export'            => 'Dışa Aktar / Yedekle',
    'import'            => 'Yedeği içe aktar',
    'restoring'         => 'Geri yükleniyor…',
    'switch_to_company' => 'Şirkete geç',

    'import_title'       => 'Bir Libre Accounting yedeğinden geri yükle',
    'import_description' => 'Şirketi bu örnekte yeniden oluşturmak için Libre Accounting üzerinden dışa aktarılmış bir yedek arşivini (.zip) yükleyin.',
    'import_action'      => 'Yedeği geri yükle',

    'messages' => [
        'export_started'    => 'Yedekleme başlatıldı. İndirme hazır olduğunda bilgilendirileceksiniz.',
        'exporting'         => 'Şirket yedeği oluşturuluyor…',
        'export_completed'  => 'Şirket yedeği hazır.',
        'import_started'    => 'Geri yükleme başlatıldı. Büyük yedeklerde bu işlem biraz zaman alabilir.',
        'importing'         => 'Şirket geri yükleniyor…',
        'import_completed'  => 'Şirket başarıyla geri yüklendi.',
        'failed'            => 'Bir şeyler ters gitti.',
    ],

    'warnings' => [
        'title'                 => 'Geri yükleme bazı notlarla tamamlandı:',
        'disabled_modules'      => 'Bu modüllere yedekte başvuruluyor ancak burada kurulu olmadıklarından devre dışı bırakıldılar: :modules.',
        'skipped_reports'       => 'Rapor türü bu örnekte kurulu olmadığından :count rapor atlandı.',
        'unbundled_media'       => ':count dosya uzak depolamada saklandığı için pakete dahil edilmedi; bunları elle yeniden yükleyin.',
        'missing_currency_refs' => ':count kayıt, yedekte bulunmayan bir para birimine başvurdu ve varsayılan para birimine geri döndü.',
    ],

    'errors' => [
        'invalid_format' => 'Yüklenen dosya geçerli bir Libre Accounting şirket yedeği değil.',
        'newer_version'  => 'Bu yedek, Libre Accounting\'in daha yeni bir sürümü tarafından oluşturuldu. Geri yüklemeden önce lütfen bu örneği yükseltin.',
    ],

];
