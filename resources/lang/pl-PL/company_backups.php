<?php

return [

    'export'            => 'Eksport / Kopia zapasowa',
    'import'            => 'Importuj kopię zapasową',
    'restoring'         => 'Przywracanie…',
    'switch_to_company' => 'Przełącz na firmę',

    'import_title'       => 'Przywróć z kopii zapasowej Libre Accounting',
    'import_description' => 'Prześlij archiwum kopii zapasowej (.zip) wyeksportowane z Libre Accounting, aby odtworzyć firmę w tej instancji.',
    'import_action'      => 'Przywróć kopię zapasową',

    'messages' => [
        'export_started'    => 'Rozpoczęto tworzenie kopii zapasowej. Powiadomimy Cię, gdy plik do pobrania będzie gotowy.',
        'exporting'         => 'Tworzenie kopii zapasowej firmy…',
        'export_completed'  => 'Kopia zapasowa firmy jest gotowa.',
        'import_started'    => 'Rozpoczęto przywracanie. W przypadku dużych kopii zapasowych może to chwilę potrwać.',
        'importing'         => 'Przywracanie firmy…',
        'import_completed'  => 'Firma została pomyślnie przywrócona.',
        'failed'            => 'Coś poszło nie tak.',
    ],

    'warnings' => [
        'title'                 => 'Przywracanie zakończyło się z pewnymi uwagami:',
        'disabled_modules'      => 'Te moduły są używane w kopii zapasowej, ale nie są tutaj zainstalowane, dlatego pozostały wyłączone: :modules.',
        'skipped_reports'       => 'Pominięto raporty (:count), ponieważ ich typ raportu nie jest zainstalowany w tej instancji.',
        'unbundled_media'       => 'Pliki (:count) były przechowywane w zewnętrznej pamięci masowej i nie zostały dołączone; prześlij je ponownie ręcznie.',
        'missing_currency_refs' => 'Rekordy (:count) odwoływały się do waluty, której nie było w kopii zapasowej, i użyto dla nich waluty domyślnej.',
    ],

    'errors' => [
        'invalid_format' => 'Przesłany plik nie jest prawidłową kopią zapasową firmy Libre Accounting.',
        'newer_version'  => 'Ta kopia zapasowa została utworzona przez nowszą wersję Libre Accounting. Przed przywróceniem zaktualizuj tę instancję.',
    ],

];
