<?php

return [

    'export'            => 'Esporta / Backup',
    'import'            => 'Importa backup',
    'restoring'         => 'Ripristino in corso…',
    'switch_to_company' => 'Passa all\'azienda',

    'import_title'       => 'Ripristina da un backup di Libre Accounting',
    'import_description' => 'Carica un archivio di backup (.zip) esportato da Libre Accounting per ricreare l\'azienda su questa istanza.',
    'import_action'      => 'Ripristina backup',

    'messages' => [
        'export_started'    => 'Backup avviato. Riceverai una notifica quando il download sarà pronto.',
        'exporting'         => 'Creazione del backup dell\'azienda in corso…',
        'export_completed'  => 'Il backup dell\'azienda è pronto.',
        'import_started'    => 'Ripristino avviato. L\'operazione potrebbe richiedere del tempo per i backup di grandi dimensioni.',
        'importing'         => 'Ripristino dell\'azienda in corso…',
        'import_completed'  => 'L\'azienda è stata ripristinata con successo.',
        'failed'            => 'Si è verificato un problema.',
    ],

    'warnings' => [
        'title'                 => 'Il ripristino è terminato con alcune note:',
        'disabled_modules'      => 'Questi moduli sono referenziati dal backup ma non sono installati qui, quindi sono stati lasciati disattivati: :modules.',
        'skipped_reports'       => ':count report sono stati ignorati perché il relativo tipo di report non è installato su questa istanza.',
        'unbundled_media'       => ':count file erano archiviati su uno storage remoto e non sono stati inclusi nel pacchetto; caricali nuovamente manualmente.',
        'missing_currency_refs' => ':count record facevano riferimento a una valuta non presente nel backup e sono stati riportati alla valuta predefinita.',
    ],

    'errors' => [
        'invalid_format' => 'Il file caricato non è un backup aziendale di Libre Accounting valido.',
        'newer_version'  => 'Questo backup è stato creato da una versione più recente di Libre Accounting. Aggiorna questa istanza prima di procedere con il ripristino.',
    ],

];
