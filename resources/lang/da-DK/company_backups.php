<?php

return [

    'export'            => 'Eksport / Backup',
    'import'            => 'Importér backup',
    'restoring'         => 'Gendanner…',
    'switch_to_company' => 'Skift til virksomhed',

    'import_title'       => 'Gendan fra en Libre Accounting-backup',
    'import_description' => 'Upload et backuparkiv (.zip) eksporteret fra Libre Accounting for at genskabe virksomheden på denne instans.',
    'import_action'      => 'Gendan backup',

    'messages' => [
        'export_started'    => 'Backup startet. Du får besked, når downloadet er klar.',
        'exporting'         => 'Bygger virksomhedens backup…',
        'export_completed'  => 'Virksomhedens backup er klar.',
        'import_started'    => 'Gendannelse startet. Dette kan tage et stykke tid for store backups.',
        'importing'         => 'Gendanner virksomheden…',
        'import_completed'  => 'Virksomheden blev gendannet.',
        'failed'            => 'Noget gik galt.',
    ],

    'warnings' => [
        'title'                 => 'Gendannelsen blev afsluttet med nogle bemærkninger:',
        'disabled_modules'      => 'Disse moduler refereres af backuppen, men er ikke installeret her, så de blev efterladt deaktiveret: :modules.',
        'skipped_reports'       => ':count rapport(er) blev sprunget over, fordi deres rapporttype ikke er installeret på denne instans.',
        'unbundled_media'       => ':count fil(er) var gemt på fjernlager og blev ikke inkluderet; upload dem manuelt igen.',
        'missing_currency_refs' => ':count post(er) refererede til en valuta, der ikke fandtes i backuppen, og faldt tilbage til standardvalutaen.',
    ],

    'errors' => [
        'invalid_format' => 'Den uploadede fil er ikke en gyldig Libre Accounting-virksomhedsbackup.',
        'newer_version'  => 'Denne backup blev oprettet af en nyere version af Libre Accounting. Opgradér denne instans, før du gendanner.',
    ],

];
