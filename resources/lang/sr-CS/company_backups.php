<?php

return [

    'export'            => 'Izvoz / Rezervna kopija',
    'import'            => 'Uvezi rezervnu kopiju',
    'restoring'         => 'Vraćanje…',
    'switch_to_company' => 'Prebaci na preduzeće',

    'import_title'       => 'Vraćanje iz Libre Accounting rezervne kopije',
    'import_description' => 'Otpremite arhivu rezervne kopije (.zip) izvezenu iz Libre Accounting da biste ponovo kreirali preduzeće na ovoj instanci.',
    'import_action'      => 'Vrati rezervnu kopiju',

    'messages' => [
        'export_started'    => 'Izrada rezervne kopije je započeta. Bićete obavešteni kada preuzimanje bude spremno.',
        'exporting'         => 'Izrada rezervne kopije preduzeća…',
        'export_completed'  => 'Rezervna kopija preduzeća je spremna.',
        'import_started'    => 'Vraćanje je započeto. Za velike rezervne kopije ovo može potrajati.',
        'importing'         => 'Vraćanje preduzeća…',
        'import_completed'  => 'Preduzeće je uspešno vraćeno.',
        'failed'            => 'Nešto je pošlo naopako.',
    ],

    'warnings' => [
        'title'                 => 'Vraćanje je završeno uz nekoliko napomena:',
        'disabled_modules'      => 'Na ove module se poziva rezervna kopija, ali ovde nisu instalirani, pa su ostavljeni onemogućeni: :modules.',
        'skipped_reports'       => 'Preskočen(o) je :count izveštaj(a) jer njihov tip izveštaja nije instaliran na ovoj instanci.',
        'unbundled_media'       => ':count datoteka je uskladišteno na udaljenom skladištu i nije uključeno u paket; ponovo ih otpremite ručno.',
        'missing_currency_refs' => ':count zapis(a) se pozivalo na valutu koja nije bila u rezervnoj kopiji i vraćeno je na podrazumevanu valutu.',
    ],

    'errors' => [
        'invalid_format' => 'Otpremljena datoteka nije važeća Libre Accounting rezervna kopija preduzeća.',
        'newer_version'  => 'Ova rezervna kopija je kreirana novijom verzijom Libre Accounting. Nadogradite ovu instancu pre vraćanja.',
    ],

];
