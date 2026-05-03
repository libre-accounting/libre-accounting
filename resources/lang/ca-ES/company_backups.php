<?php

return [

    'export'            => 'Exporta / Còpia de seguretat',
    'import'            => 'Importa còpia de seguretat',
    'restoring'         => 'S\'està restaurant…',
    'switch_to_company' => 'Canvia a l\'empresa',

    'import_title'       => 'Restaura des d\'una còpia de seguretat de Libre Accounting',
    'import_description' => 'Puja un arxiu de còpia de seguretat (.zip) exportat des de Libre Accounting per tornar a crear l\'empresa en aquesta instància.',
    'import_action'      => 'Restaura la còpia de seguretat',

    'messages' => [
        'export_started'    => 'S\'ha iniciat la còpia de seguretat. Se us notificarà quan la descàrrega estigui a punt.',
        'exporting'         => 'S\'està creant la còpia de seguretat de l\'empresa…',
        'export_completed'  => 'La còpia de seguretat de l\'empresa està a punt.',
        'import_started'    => 'S\'ha iniciat la restauració. Pot trigar una estona amb còpies de seguretat grans.',
        'importing'         => 'S\'està restaurant l\'empresa…',
        'import_completed'  => 'L\'empresa s\'ha restaurat correctament.',
        'failed'            => 'Alguna cosa ha anat malament.',
    ],

    'warnings' => [
        'title'                 => 'La restauració ha finalitzat amb algunes notes:',
        'disabled_modules'      => 'Aquests mòduls estan referenciats per la còpia de seguretat però no estan instal·lats aquí, de manera que s\'han deixat desactivats: :modules.',
        'skipped_reports'       => 'S\'han omès :count informe(s) perquè el seu tipus d\'informe no està instal·lat en aquesta instància.',
        'unbundled_media'       => 'Hi ha :count fitxer(s) que estaven emmagatzemats en un emmagatzematge remot i no s\'han inclòs; torneu-los a pujar manualment.',
        'missing_currency_refs' => 'Hi ha :count registre(s) que feien referència a una moneda que no era a la còpia de seguretat i han recorregut a la moneda per defecte.',
    ],

    'errors' => [
        'invalid_format' => 'El fitxer pujat no és una còpia de seguretat d\'empresa vàlida de Libre Accounting.',
        'newer_version'  => 'Aquesta còpia de seguretat s\'ha creat amb una versió més nova de Libre Accounting. Actualitzeu aquesta instància abans de restaurar-la.',
    ],

];
