<?php

return [

    'export'            => 'Izvoz / Sigurnosna kopija',
    'import'            => 'Uvoz sigurnosne kopije',
    'restoring'         => 'Vraćanje…',
    'switch_to_company' => 'Prebaci na firmu',

    'import_title'       => 'Vraćanje iz Libre Accounting sigurnosne kopije',
    'import_description' => 'Otpremite arhivu sigurnosne kopije (.zip) izvezenu iz Libre Accounting da biste ponovo kreirali firmu na ovoj instanci.',
    'import_action'      => 'Vrati sigurnosnu kopiju',

    'messages' => [
        'export_started'    => 'Izrada sigurnosne kopije je započela. Bićete obaviješteni kada preuzimanje bude spremno.',
        'exporting'         => 'Izrada sigurnosne kopije firme…',
        'export_completed'  => 'Sigurnosna kopija firme je spremna.',
        'import_started'    => 'Vraćanje je započelo. Za velike sigurnosne kopije ovo može potrajati.',
        'importing'         => 'Vraćanje firme…',
        'import_completed'  => 'Firma je uspješno vraćena.',
        'failed'            => 'Nešto je pošlo po zlu.',
    ],

    'warnings' => [
        'title'                 => 'Vraćanje je završeno uz nekoliko napomena:',
        'disabled_modules'      => 'Ovi moduli se navode u sigurnosnoj kopiji, ali ovdje nisu instalirani, pa su ostavljeni onemogućenim: :modules.',
        'skipped_reports'       => ':count izvještaj(a) je preskočeno jer njihov tip izvještaja nije instaliran na ovoj instanci.',
        'unbundled_media'       => ':count datoteka(e) je bilo pohranjeno na udaljenom skladištu i nije uključeno u paket; otpremite ih ponovo ručno.',
        'missing_currency_refs' => ':count zapis(a) se pozivalo na valutu koje nije bilo u sigurnosnoj kopiji te je vraćeno na podrazumijevanu valutu.',
    ],

    'errors' => [
        'invalid_format' => 'Otpremljena datoteka nije važeća Libre Accounting sigurnosna kopija firme.',
        'newer_version'  => 'Ova sigurnosna kopija je kreirana novijom verzijom Libre Accounting. Nadogradite ovu instancu prije vraćanja.',
    ],

];
