<?php

return [

    'export'            => 'İxrac / Ehtiyat nüsxə',
    'import'            => 'Ehtiyat nüsxəni idxal et',
    'restoring'         => 'Bərpa edilir…',
    'switch_to_company' => 'Şirkətə keç',

    'import_title'       => 'Libre Accounting ehtiyat nüsxəsindən bərpa et',
    'import_description' => 'Bu instansiyada şirkəti yenidən yaratmaq üçün Libre Accounting-dən ixrac edilmiş ehtiyat nüsxə arxivini (.zip) yükləyin.',
    'import_action'      => 'Ehtiyat nüsxəni bərpa et',

    'messages' => [
        'export_started'    => 'Ehtiyat nüsxə başladıldı. Yükləmə hazır olduqda sizə bildiriş göndəriləcək.',
        'exporting'         => 'Şirkət ehtiyat nüsxəsi yaradılır…',
        'export_completed'  => 'Şirkət ehtiyat nüsxəsi hazırdır.',
        'import_started'    => 'Bərpa başladıldı. Böyük ehtiyat nüsxələr üçün bu bir qədər vaxt ala bilər.',
        'importing'         => 'Şirkət bərpa edilir…',
        'import_completed'  => 'Şirkət uğurla bərpa edildi.',
        'failed'            => 'Nəsə səhv getdi.',
    ],

    'warnings' => [
        'title'                 => 'Bərpa bəzi qeydlərlə tamamlandı:',
        'disabled_modules'      => 'Bu modullar ehtiyat nüsxədə istinad edilib, lakin burada quraşdırılmayıb, buna görə də söndürülmüş vəziyyətdə saxlanıldı: :modules.',
        'skipped_reports'       => ':count hesabat ötürüldü, çünki onların hesabat növü bu instansiyada quraşdırılmayıb.',
        'unbundled_media'       => ':count fayl uzaq yaddaşda saxlanılmışdı və dəstələnmədi; onları əl ilə yenidən yükləyin.',
        'missing_currency_refs' => ':count qeyd ehtiyat nüsxədə olmayan valyutaya istinad etdi və standart valyutaya keçdi.',
    ],

    'errors' => [
        'invalid_format' => 'Yüklənmiş fayl etibarlı Libre Accounting şirkət ehtiyat nüsxəsi deyil.',
        'newer_version'  => 'Bu ehtiyat nüsxə Libre Accounting-in daha yeni versiyası tərəfindən yaradılıb. Bərpa etməzdən əvvəl bu instansiyanı yeniləyin.',
    ],

];
