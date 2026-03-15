<?php

return [

    'form_description'  => 'Upload een CAMT.053 (ISO 20022 XML) bankafschrift om de transacties te importeren. De regels worden klaargezet voor controle voordat er iets aan uw boekhouding wordt toegevoegd.',
    'account'           => 'Rekening',
    'file'              => 'Afschriftbestand',

    'statement'         => 'Afschrift',
    'period'            => 'Periode',
    'booked_at'         => 'Geboekt',
    'value_date'        => 'Valutadatum',
    'counterparty'      => 'Tegenpartij',
    'remittance'        => 'Betalingskenmerk',
    'reference'         => 'Referentie',
    'lines'             => 'Regels',
    'imported'          => 'Geïmporteerd',
    'total_lines'       => 'Totaal aantal regels',
    'imported_lines'    => 'Geïmporteerde regels',

    'statuses' => [
        'pending'   => 'In afwachting',
        'imported'  => 'Geïmporteerd',
        'skipped'   => 'Overgeslagen',
        'duplicate' => 'Duplicaat',
        'reviewing' => 'In controle',
        'completed' => 'Voltooid',
    ],

    'import_selected'   => 'Selectie importeren',
    'select_all'        => 'Alles selecteren',
    'set_category_all'  => 'Categorie instellen voor alle geselecteerde',

    'messages' => [
        'staged'        => ':count regel(s) verwerkt en klaar voor controle.',
        'committed'     => ':count transactie(s) aangemaakt.',
        'truncated'     => 'Het afschrift was groot: :count regel(s) boven de limiet zijn niet geïmporteerd.',
        'iban_mismatch' => 'Het IBAN van het afschrift komt niet overeen met de geselecteerde rekening. Controleer de rekening nogmaals.',
    ],

    'errors' => [
        'not_xml'           => 'Het afschrift moet een CAMT.053 XML-bestand zijn.',
        'unreadable'        => 'Het geüploade bestand kon niet worden gelezen.',
        'already_imported'  => 'Dit exacte bestand is al geïmporteerd.',
        'staging_failed'    => 'Het afschrift kon niet worden voorbereid voor beoordeling. Probeer het opnieuw.',
        'none_selected'     => 'Selecteer ten minste één regel om te importeren.',
        'category_required' => 'Kies een categorie voor elke geselecteerde regel voordat u importeert.',
    ],

];
