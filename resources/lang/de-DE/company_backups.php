<?php

return [

    'export'            => 'Export / Backup',
    'import'            => 'Backup importieren',
    'restoring'         => 'Wird wiederhergestellt…',
    'switch_to_company' => 'Zum Unternehmen wechseln',

    'import_title'       => 'Aus einem Libre Accounting-Backup wiederherstellen',
    'import_description' => 'Laden Sie ein aus Libre Accounting exportiertes Backup-Archiv (.zip) hoch, um das Unternehmen auf dieser Instanz neu anzulegen.',
    'import_action'      => 'Backup wiederherstellen',

    'messages' => [
        'export_started'    => 'Backup gestartet. Sie werden benachrichtigt, sobald der Download bereitsteht.',
        'exporting'         => 'Unternehmens-Backup wird erstellt…',
        'export_completed'  => 'Das Unternehmens-Backup ist bereit.',
        'import_started'    => 'Wiederherstellung gestartet. Bei großen Backups kann dies eine Weile dauern.',
        'importing'         => 'Unternehmen wird wiederhergestellt…',
        'import_completed'  => 'Das Unternehmen wurde erfolgreich wiederhergestellt.',
        'failed'            => 'Etwas ist schiefgelaufen.',
    ],

    'warnings' => [
        'title'                 => 'Die Wiederherstellung wurde mit einigen Hinweisen abgeschlossen:',
        'disabled_modules'      => 'Diese Module werden vom Backup referenziert, sind hier aber nicht installiert und wurden daher deaktiviert gelassen: :modules.',
        'skipped_reports'       => ':count Bericht(e) wurden übersprungen, da ihr Berichtstyp auf dieser Instanz nicht installiert ist.',
        'unbundled_media'       => ':count Datei(en) waren auf externem Speicher abgelegt und wurden nicht eingebunden; laden Sie sie manuell erneut hoch.',
        'missing_currency_refs' => ':count Datensatz/Datensätze referenzierten eine Währung, die nicht im Backup enthalten war, und griffen auf die Standardwährung zurück.',
    ],

    'errors' => [
        'invalid_format' => 'Die hochgeladene Datei ist kein gültiges Libre Accounting-Unternehmens-Backup.',
        'newer_version'  => 'Dieses Backup wurde mit einer neueren Version von Libre Accounting erstellt. Bitte aktualisieren Sie diese Instanz vor der Wiederherstellung.',
    ],

];
