<?php

return [

    'export'            => 'Izvoz / Rezervna kopija',
    'import'            => 'Uvoz rezervne kopije',
    'restoring'         => 'Vraćanje…',
    'switch_to_company' => 'Prebaci na kompaniju',

    'import_title'       => 'Vraćanje iz Libre Accounting rezervne kopije',
    'import_description' => 'Otpremite arhivu rezervne kopije (.zip) izvezenu iz Libre Accounting da biste ponovo kreirali kompaniju na ovoj instanci.',
    'import_action'      => 'Vrati rezervnu kopiju',

    'messages' => [
        'export_started'    => 'Izrada rezervne kopije je započeta. Bićete obavešteni kada preuzimanje bude spremno.',
        'exporting'         => 'Izrada rezervne kopije kompanije…',
        'export_completed'  => 'Rezervna kopija kompanije je spremna.',
        'import_started'    => 'Vraćanje je započeto. Za velike rezervne kopije ovo može potrajati.',
        'importing'         => 'Vraćanje kompanije…',
        'import_completed'  => 'Kompanija je uspešno vraćena.',
        'failed'            => 'Nešto je pošlo naopako.',
    ],

    'warnings' => [
        'title'                 => 'Vraćanje je završeno uz nekoliko napomena:',
        'disabled_modules'      => 'Rezervna kopija upućuje na ove module, ali oni ovde nisu instalirani, pa su ostavljeni onemogućeni: :modules.',
        'skipped_reports'       => 'Preskočeno je :count izveštaj(a) jer njihov tip izveštaja nije instaliran na ovoj instanci.',
        'unbundled_media'       => ':count datoteka je bilo uskladišteno na udaljenom skladištu i nije uključeno u paket; otpremite ih ponovo ručno.',
        'missing_currency_refs' => ':count zapis(a) je upućivalo na valutu koja nije bila u rezervnoj kopiji i vraćeno je na podrazumevanu valutu.',
    ],

    'errors' => [
        'invalid_format' => 'Otpremljena datoteka nije važeća Libre Accounting rezervna kopija kompanije.',
        'newer_version'  => 'Ova rezervna kopija je kreirana novijom verzijom Libre Accounting. Nadogradite ovu instancu pre vraćanja.',
    ],

];
