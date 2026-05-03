<?php

return [

    'export'            => 'Export / Backup',
    'import'            => 'Importă backup',
    'restoring'         => 'Se restaurează…',
    'switch_to_company' => 'Comută la companie',

    'import_title'       => 'Restaurează dintr-un backup Libre Accounting',
    'import_description' => 'Încarcă o arhivă de backup (.zip) exportată din Libre Accounting pentru a recrea compania pe această instanță.',
    'import_action'      => 'Restaurează backup-ul',

    'messages' => [
        'export_started'    => 'Backup-ul a început. Vei fi notificat când descărcarea este gata.',
        'exporting'         => 'Se creează backup-ul companiei…',
        'export_completed'  => 'Backup-ul companiei este gata.',
        'import_started'    => 'Restaurarea a început. Poate dura ceva timp pentru backup-uri mari.',
        'importing'         => 'Se restaurează compania…',
        'import_completed'  => 'Compania a fost restaurată cu succes.',
        'failed'            => 'Ceva nu a mers bine.',
    ],

    'warnings' => [
        'title'                 => 'Restaurarea s-a finalizat cu câteva observații:',
        'disabled_modules'      => 'Aceste module sunt referite de backup, dar nu sunt instalate aici, așa că au fost lăsate dezactivate: :modules.',
        'skipped_reports'       => ':count raport(oarte) au fost omise deoarece tipul lor de raport nu este instalat pe această instanță.',
        'unbundled_media'       => ':count fișier(e) au fost stocate în stocare la distanță și nu au fost incluse; reîncarcă-le manual.',
        'missing_currency_refs' => ':count înregistrare(ări) au referit o monedă care nu se afla în backup și au revenit la moneda implicită.',
    ],

    'errors' => [
        'invalid_format' => 'Fișierul încărcat nu este un backup valid de companie Libre Accounting.',
        'newer_version'  => 'Acest backup a fost creat de o versiune mai nouă de Libre Accounting. Actualizează această instanță înainte de restaurare.',
    ],

];
