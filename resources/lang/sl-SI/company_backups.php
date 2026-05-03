<?php

return [

    'export'            => 'Izvoz / varnostna kopija',
    'import'            => 'Uvoz varnostne kopije',
    'restoring'         => 'Obnavljanje…',
    'switch_to_company' => 'Preklopi na podjetje',

    'import_title'       => 'Obnovitev iz varnostne kopije Libre Accounting',
    'import_description' => 'Naložite arhiv varnostne kopije (.zip), izvožen iz Libre Accounting, da na tej instanci znova ustvarite podjetje.',
    'import_action'      => 'Obnovi varnostno kopijo',

    'messages' => [
        'export_started'    => 'Varnostno kopiranje se je začelo. Ko bo prenos pripravljen, boste obveščeni.',
        'exporting'         => 'Ustvarjanje varnostne kopije podjetja…',
        'export_completed'  => 'Varnostna kopija podjetja je pripravljena.',
        'import_started'    => 'Obnavljanje se je začelo. Pri velikih varnostnih kopijah lahko traja nekaj časa.',
        'importing'         => 'Obnavljanje podjetja…',
        'import_completed'  => 'Podjetje je bilo uspešno obnovljeno.',
        'failed'            => 'Nekaj je šlo narobe.',
    ],

    'warnings' => [
        'title'                 => 'Obnovitev se je zaključila z nekaj opombami:',
        'disabled_modules'      => 'Ti moduli so navedeni v varnostni kopiji, vendar niso nameščeni tukaj, zato so ostali onemogočeni: :modules.',
        'skipped_reports'       => ':count poročil je bilo preskočenih, ker vrsta njihovega poročila ni nameščena na tej instanci.',
        'unbundled_media'       => ':count datotek je bilo shranjenih na oddaljeni shrambi in niso bile priložene; naložite jih znova ročno.',
        'missing_currency_refs' => ':count zapisov se je sklicevalo na valuto, ki je ni bilo v varnostni kopiji, in so uporabili privzeto valuto.',
    ],

    'errors' => [
        'invalid_format' => 'Naložena datoteka ni veljavna varnostna kopija podjetja Libre Accounting.',
        'newer_version'  => 'Ta varnostna kopija je bila ustvarjena z novejšo različico Libre Accounting. Pred obnovitvijo posodobite to instanco.',
    ],

];
