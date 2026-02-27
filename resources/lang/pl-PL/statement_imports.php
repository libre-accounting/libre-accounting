<?php

return [

    'form_description'  => 'Prześlij wyciąg bankowy CAMT.053 (ISO 20022 XML), aby zaimportować jego transakcje. Pozycje są przygotowywane do przeglądu, zanim cokolwiek zostanie dodane do Twoich ksiąg.',
    'account'           => 'Konto',
    'file'              => 'Plik wyciągu',

    'statement'         => 'Wyciąg',
    'period'            => 'Okres',
    'booked_at'         => 'Zaksięgowano',
    'value_date'        => 'Data waluty',
    'counterparty'      => 'Kontrahent',
    'remittance'        => 'Informacje o płatności',
    'reference'         => 'Odwołanie',
    'lines'             => 'Pozycje',
    'imported'          => 'Zaimportowano',
    'total_lines'       => 'Wszystkie pozycje',
    'imported_lines'    => 'Zaimportowane pozycje',

    'statuses' => [
        'pending'   => 'Oczekujące',
        'imported'  => 'Zaimportowane',
        'skipped'   => 'Pominięte',
        'duplicate' => 'Duplikat',
        'reviewing' => 'W trakcie przeglądu',
        'completed' => 'Zakończone',
    ],

    'import_selected'   => 'Importuj zaznaczone',
    'select_all'        => 'Zaznacz wszystko',
    'set_category_all'  => 'Ustaw kategorię dla wszystkich zaznaczonych',

    'messages' => [
        'staged'        => 'Przeanalizowano :count pozycji gotowych do przeglądu.',
        'committed'     => 'Utworzono :count transakcji.',
        'truncated'     => 'Wyciąg był duży: :count pozycji powyżej limitu nie zostało zaimportowanych.',
        'iban_mismatch' => 'IBAN z wyciągu nie zgadza się z wybranym kontem. Sprawdź ponownie konto.',
    ],

    'errors' => [
        'not_xml'           => 'Wyciąg musi być plikiem CAMT.053 XML.',
        'unreadable'        => 'Nie udało się odczytać przesłanego pliku.',
        'already_imported'  => 'Ten sam plik został już zaimportowany.',
        'none_selected'     => 'Zaznacz co najmniej jedną pozycję do zaimportowania.',
        'category_required' => 'Wybierz kategorię dla każdej zaznaczonej pozycji przed importem.',
    ],

];
