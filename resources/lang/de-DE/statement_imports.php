<?php

return [

    'form_description'  => 'Laden Sie einen CAMT.053-Kontoauszug (ISO 20022 XML) hoch, um dessen Transaktionen zu importieren. Die Zeilen werden zur Prüfung vorgemerkt, bevor etwas in Ihre Buchhaltung übernommen wird.',
    'account'           => 'Konto',
    'file'              => 'Auszugsdatei',

    'statement'         => 'Kontoauszug',
    'period'            => 'Zeitraum',
    'booked_at'         => 'Gebucht',
    'value_date'        => 'Wertstellungsdatum',
    'counterparty'      => 'Gegenpartei',
    'remittance'        => 'Verwendungszweck',
    'reference'         => 'Referenz',
    'lines'             => 'Zeilen',
    'imported'          => 'Importiert',
    'total_lines'       => 'Zeilen gesamt',
    'imported_lines'    => 'Importierte Zeilen',

    'statuses' => [
        'pending'   => 'Ausstehend',
        'imported'  => 'Importiert',
        'skipped'   => 'Übersprungen',
        'duplicate' => 'Duplikat',
        'reviewing' => 'In Prüfung',
        'completed' => 'Abgeschlossen',
    ],

    'import_selected'   => 'Auswahl importieren',
    'select_all'        => 'Alle auswählen',
    'set_category_all'  => 'Kategorie für alle ausgewählten festlegen',

    'messages' => [
        'staged'        => ':count Zeile(n) eingelesen und zur Prüfung bereit.',
        'committed'     => ':count Transaktion(en) erstellt.',
        'truncated'     => 'Der Kontoauszug war umfangreich: :count Zeile(n) über dem Limit wurden nicht importiert.',
        'iban_mismatch' => 'Die IBAN des Kontoauszugs stimmt nicht mit dem ausgewählten Konto überein. Bitte überprüfen Sie das Konto.',
    ],

    'errors' => [
        'not_xml'           => 'Der Kontoauszug muss eine CAMT.053-XML-Datei sein.',
        'unreadable'        => 'Die hochgeladene Datei konnte nicht gelesen werden.',
        'already_imported'  => 'Genau diese Datei wurde bereits importiert.',
        'staging_failed'    => 'Der Kontoauszug konnte nicht zur Überprüfung bereitgestellt werden. Bitte versuchen Sie es erneut.',
        'none_selected'     => 'Wählen Sie mindestens eine Zeile zum Importieren aus.',
        'category_required' => 'Wählen Sie vor dem Importieren für jede ausgewählte Zeile eine Kategorie.',
    ],

];
