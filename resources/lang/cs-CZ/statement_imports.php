<?php

return [

    'form_description'  => 'Nahrajte bankovní výpis ve formátu CAMT.053 (ISO 20022 XML) pro import jeho transakcí. Řádky jsou připraveny ke kontrole dříve, než se cokoli přidá do vašeho účetnictví.',
    'account'           => 'Účet',
    'file'              => 'Soubor výpisu',

    'statement'         => 'Výpis',
    'period'            => 'Období',
    'booked_at'         => 'Zaúčtováno',
    'value_date'        => 'Datum valuty',
    'counterparty'      => 'Protistrana',
    'remittance'        => 'Informace o platbě',
    'reference'         => 'Reference',
    'lines'             => 'Řádky',
    'imported'          => 'Importováno',
    'total_lines'       => 'Celkem řádků',
    'imported_lines'    => 'Importované řádky',

    'statuses' => [
        'pending'   => 'Čeká na zpracování',
        'imported'  => 'Importováno',
        'skipped'   => 'Přeskočeno',
        'duplicate' => 'Duplicitní',
        'reviewing' => 'Kontroluje se',
        'completed' => 'Dokončeno',
    ],

    'import_selected'   => 'Importovat vybrané',
    'select_all'        => 'Vybrat vše',
    'set_category_all'  => 'Nastavit kategorii pro všechny vybrané',

    'messages' => [
        'staged'        => 'Zpracováno :count řádků a připraveno ke kontrole.',
        'committed'     => 'Vytvořeno :count transakcí.',
        'truncated'     => 'Výpis byl rozsáhlý: :count řádků nad rámec limitu nebylo importováno.',
        'iban_mismatch' => 'IBAN ve výpisu neodpovídá vybranému účtu. Zkontrolujte prosím účet.',
    ],

    'errors' => [
        'not_xml'           => 'Výpis musí být soubor CAMT.053 ve formátu XML.',
        'unreadable'        => 'Nahraný soubor se nepodařilo přečíst.',
        'already_imported'  => 'Tento soubor již byl importován.',
        'staging_failed'    => 'Výpis se nepodařilo připravit ke kontrole. Zkuste to prosím znovu.',
        'none_selected'     => 'Vyberte alespoň jeden řádek k importu.',
        'category_required' => 'Před importem zvolte kategorii pro každý vybraný řádek.',
    ],

];
