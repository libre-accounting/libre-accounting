<?php

return [

    'export'            => 'Exportálás / Biztonsági mentés',
    'import'            => 'Biztonsági mentés importálása',
    'restoring'         => 'Visszaállítás…',
    'switch_to_company' => 'Váltás a cégre',

    'import_title'       => 'Visszaállítás Libre Accounting biztonsági mentésből',
    'import_description' => 'Töltsön fel egy Libre Accounting alkalmazásból exportált biztonsági mentési archívumot (.zip), hogy a cég újra létrejöjjön ezen a példányon.',
    'import_action'      => 'Biztonsági mentés visszaállítása',

    'messages' => [
        'export_started'    => 'A biztonsági mentés elindult. Értesítést kap, amikor a letöltés elkészül.',
        'exporting'         => 'A cég biztonsági mentésének készítése…',
        'export_completed'  => 'A cég biztonsági mentése elkészült.',
        'import_started'    => 'A visszaállítás elindult. Nagy méretű mentéseknél ez eltarthat egy ideig.',
        'importing'         => 'A cég visszaállítása…',
        'import_completed'  => 'A cég visszaállítása sikeresen megtörtént.',
        'failed'            => 'Hiba történt.',
    ],

    'warnings' => [
        'title'                 => 'A visszaállítás néhány megjegyzéssel fejeződött be:',
        'disabled_modules'      => 'A biztonsági mentés hivatkozik ezekre a modulokra, de azok itt nincsenek telepítve, ezért letiltva maradtak: :modules.',
        'skipped_reports'       => ':count jelentés kihagyásra került, mert a jelentéstípusuk nincs telepítve ezen a példányon.',
        'unbundled_media'       => ':count fájl távoli tárhelyen volt tárolva, és nem került a csomagba; töltse fel őket újra manuálisan.',
        'missing_currency_refs' => ':count rekord olyan pénznemre hivatkozott, amely nem szerepelt a biztonsági mentésben, ezért az alapértelmezett pénznemre állt vissza.',
    ],

    'errors' => [
        'invalid_format' => 'A feltöltött fájl nem érvényes Libre Accounting cégbiztonsági mentés.',
        'newer_version'  => 'Ezt a biztonsági mentést a Libre Accounting egy újabb verziója hozta létre. A visszaállítás előtt frissítse ezt a példányt.',
    ],

];
