<?php

return [

    'export'            => 'Izvoz / Sigurnosna kopija',
    'import'            => 'Uvoz sigurnosne kopije',
    'restoring'         => 'Vraćanje…',
    'switch_to_company' => 'Prebaci na tvrtku',

    'import_title'       => 'Vraćanje iz Libre Accounting sigurnosne kopije',
    'import_description' => 'Prenesite arhivu sigurnosne kopije (.zip) izvezenu iz Libre Accounting kako biste ponovno stvorili tvrtku na ovoj instanci.',
    'import_action'      => 'Vrati sigurnosnu kopiju',

    'messages' => [
        'export_started'    => 'Izrada sigurnosne kopije je započela. Bit ćete obaviješteni kada preuzimanje bude spremno.',
        'exporting'         => 'Izrada sigurnosne kopije tvrtke…',
        'export_completed'  => 'Sigurnosna kopija tvrtke je spremna.',
        'import_started'    => 'Vraćanje je započelo. To može potrajati kod velikih sigurnosnih kopija.',
        'importing'         => 'Vraćanje tvrtke…',
        'import_completed'  => 'Tvrtka je uspješno vraćena.',
        'failed'            => 'Nešto je pošlo po zlu.',
    ],

    'warnings' => [
        'title'                 => 'Vraćanje je završeno uz nekoliko napomena:',
        'disabled_modules'      => 'Ovi moduli se referenciraju u sigurnosnoj kopiji, ali ovdje nisu instalirani, pa su ostavljeni onemogućeni: :modules.',
        'skipped_reports'       => 'Preskočeno je :count izvještaja jer njihova vrsta izvještaja nije instalirana na ovoj instanci.',
        'unbundled_media'       => ':count datoteka je pohranjeno na udaljenoj pohrani i nije uključeno u paket; prenesite ih ponovno ručno.',
        'missing_currency_refs' => ':count zapisa se referenciralo na valutu koje nije bilo u sigurnosnoj kopiji te je vraćeno na zadanu valutu.',
    ],

    'errors' => [
        'invalid_format' => 'Prenesena datoteka nije valjana Libre Accounting sigurnosna kopija tvrtke.',
        'newer_version'  => 'Ova sigurnosna kopija je stvorena novijom verzijom Libre Accounting. Nadogradite ovu instancu prije vraćanja.',
    ],

];
