<?php

return [

    'export'            => 'Exportera / Säkerhetskopiera',
    'import'            => 'Importera säkerhetskopia',
    'restoring'         => 'Återställer…',
    'switch_to_company' => 'Byt till företag',

    'import_title'       => 'Återställ från en säkerhetskopia av Libre Accounting',
    'import_description' => 'Ladda upp ett säkerhetskopiearkiv (.zip) som exporterats från Libre Accounting för att återskapa företaget på den här instansen.',
    'import_action'      => 'Återställ säkerhetskopia',

    'messages' => [
        'export_started'    => 'Säkerhetskopieringen har startat. Du meddelas när nedladdningen är klar.',
        'exporting'         => 'Bygger säkerhetskopian av företaget…',
        'export_completed'  => 'Säkerhetskopian av företaget är klar.',
        'import_started'    => 'Återställningen har startat. Det kan ta en stund för stora säkerhetskopior.',
        'importing'         => 'Återställer företaget…',
        'import_completed'  => 'Företaget återställdes utan problem.',
        'failed'            => 'Något gick fel.',
    ],

    'warnings' => [
        'title'                 => 'Återställningen slutfördes med några anmärkningar:',
        'disabled_modules'      => 'Dessa moduler refereras av säkerhetskopian men är inte installerade här, så de lämnades inaktiverade: :modules.',
        'skipped_reports'       => ':count rapport(er) hoppades över eftersom deras rapporttyp inte är installerad på den här instansen.',
        'unbundled_media'       => ':count fil(er) lagrades på fjärrlagring och inkluderades inte; ladda upp dem manuellt igen.',
        'missing_currency_refs' => ':count post(er) refererade till en valuta som inte fanns i säkerhetskopian och återgick till standardvalutan.',
    ],

    'errors' => [
        'invalid_format' => 'Den uppladdade filen är inte en giltig säkerhetskopia av ett Libre Accounting-företag.',
        'newer_version'  => 'Den här säkerhetskopian skapades av en nyare version av Libre Accounting. Uppgradera den här instansen innan du återställer.',
    ],

];
