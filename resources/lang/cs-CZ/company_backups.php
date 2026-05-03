<?php

return [

    'export'            => 'Export / Záloha',
    'import'            => 'Import zálohy',
    'restoring'         => 'Obnovuje se…',
    'switch_to_company' => 'Přepnout na společnost',

    'import_title'       => 'Obnovit ze zálohy Libre Accounting',
    'import_description' => 'Nahrajte archiv se zálohou (.zip) vyexportovaný z Libre Accounting a znovu vytvořte společnost v této instanci.',
    'import_action'      => 'Obnovit zálohu',

    'messages' => [
        'export_started'    => 'Zálohování bylo zahájeno. Až bude soubor ke stažení připraven, budete upozorněni.',
        'exporting'         => 'Vytváří se záloha společnosti…',
        'export_completed'  => 'Záloha společnosti je připravena.',
        'import_started'    => 'Obnovení bylo zahájeno. U velkých záloh to může chvíli trvat.',
        'importing'         => 'Obnovuje se společnost…',
        'import_completed'  => 'Společnost byla úspěšně obnovena.',
        'failed'            => 'Něco se pokazilo.',
    ],

    'warnings' => [
        'title'                 => 'Obnovení bylo dokončeno s několika poznámkami:',
        'disabled_modules'      => 'Tyto moduly jsou v záloze uvedeny, ale zde nejsou nainstalovány, proto byly ponechány deaktivované: :modules.',
        'skipped_reports'       => 'Počet přeskočených výkazů: :count, protože jejich typ výkazu není v této instanci nainstalován.',
        'unbundled_media'       => 'Počet souborů uložených ve vzdáleném úložišti, které nebyly zahrnuty: :count; nahrajte je ručně znovu.',
        'missing_currency_refs' => 'Počet záznamů, které odkazovaly na měnu neobsaženou v záloze a použily výchozí měnu: :count.',
    ],

    'errors' => [
        'invalid_format' => 'Nahraný soubor není platnou zálohou společnosti Libre Accounting.',
        'newer_version'  => 'Tato záloha byla vytvořena novější verzí Libre Accounting. Před obnovením prosím aktualizujte tuto instanci.',
    ],

];
