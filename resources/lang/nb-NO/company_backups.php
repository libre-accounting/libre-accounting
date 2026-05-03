<?php

return [

    'export'            => 'Eksporter / Sikkerhetskopi',
    'import'            => 'Importer sikkerhetskopi',
    'restoring'         => 'Gjenoppretter…',
    'switch_to_company' => 'Bytt til selskap',

    'import_title'       => 'Gjenopprett fra en Libre Accounting-sikkerhetskopi',
    'import_description' => 'Last opp et sikkerhetskopiarkiv (.zip) eksportert fra Libre Accounting for å gjenopprette selskapet på denne instansen.',
    'import_action'      => 'Gjenopprett sikkerhetskopi',

    'messages' => [
        'export_started'    => 'Sikkerhetskopiering startet. Du får beskjed når nedlastingen er klar.',
        'exporting'         => 'Bygger sikkerhetskopien av selskapet…',
        'export_completed'  => 'Sikkerhetskopien av selskapet er klar.',
        'import_started'    => 'Gjenoppretting startet. Dette kan ta en stund for store sikkerhetskopier.',
        'importing'         => 'Gjenoppretter selskapet…',
        'import_completed'  => 'Selskapet ble gjenopprettet.',
        'failed'            => 'Noe gikk galt.',
    ],

    'warnings' => [
        'title'                 => 'Gjenopprettingen ble fullført med noen merknader:',
        'disabled_modules'      => 'Disse modulene refereres til i sikkerhetskopien, men er ikke installert her, så de ble deaktivert: :modules.',
        'skipped_reports'       => ':count rapport(er) ble hoppet over fordi rapporttypen ikke er installert på denne instansen.',
        'unbundled_media'       => ':count fil(er) ble lagret på ekstern lagring og ble ikke inkludert; last dem opp på nytt manuelt.',
        'missing_currency_refs' => ':count post(er) refererte til en valuta som ikke var i sikkerhetskopien, og gikk tilbake til standardvalutaen.',
    ],

    'errors' => [
        'invalid_format' => 'Den opplastede filen er ikke en gyldig Libre Accounting-sikkerhetskopi av et selskap.',
        'newer_version'  => 'Denne sikkerhetskopien ble opprettet av en nyere versjon av Libre Accounting. Oppgrader denne instansen før du gjenoppretter.',
    ],

];
