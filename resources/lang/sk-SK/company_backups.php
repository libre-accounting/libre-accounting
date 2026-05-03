<?php

return [

    'export'            => 'Export / Záloha',
    'import'            => 'Import zálohy',
    'restoring'         => 'Obnovuje sa…',
    'switch_to_company' => 'Prepnúť na spoločnosť',

    'import_title'       => 'Obnoviť zo zálohy Libre Accounting',
    'import_description' => 'Nahrajte archív so zálohou (.zip) exportovaný z Libre Accounting a znovu vytvorte spoločnosť na tejto inštancii.',
    'import_action'      => 'Obnoviť zálohu',

    'messages' => [
        'export_started'    => 'Zálohovanie sa spustilo. Upozorníme vás, keď bude súbor na stiahnutie pripravený.',
        'exporting'         => 'Vytvára sa záloha spoločnosti…',
        'export_completed'  => 'Záloha spoločnosti je pripravená.',
        'import_started'    => 'Obnovovanie sa spustilo. Pri veľkých zálohách to môže chvíľu trvať.',
        'importing'         => 'Obnovuje sa spoločnosť…',
        'import_completed'  => 'Spoločnosť bola úspešne obnovená.',
        'failed'            => 'Niečo sa pokazilo.',
    ],

    'warnings' => [
        'title'                 => 'Obnovenie sa dokončilo s niekoľkými poznámkami:',
        'disabled_modules'      => 'Na tieto moduly sa záloha odkazuje, ale tu nie sú nainštalované, preto zostali vypnuté: :modules.',
        'skipped_reports'       => ':count zostáv(a/y) bolo(-i) preskočených, pretože ich typ zostavy nie je nainštalovaný na tejto inštancii.',
        'unbundled_media'       => ':count súbor(y/ov) bol(-i) uložený(-é) vo vzdialenom úložisku a neboli súčasťou balíka; nahrajte ich znova ručne.',
        'missing_currency_refs' => ':count záznam(y/ov) sa odkazoval(-i) na menu, ktorá nebola v zálohe, a použil(-i) sa predvolená mena.',
    ],

    'errors' => [
        'invalid_format' => 'Nahraný súbor nie je platnou zálohou spoločnosti Libre Accounting.',
        'newer_version'  => 'Táto záloha bola vytvorená novšou verziou Libre Accounting. Pred obnovením aktualizujte túto inštanciu.',
    ],

];
