<?php

return [

    'stock'                 => 'Magazyn',
    'track_stock'           => 'Śledź stan magazynowy',
    'untrack_stock'         => 'Przestań śledzić stan magazynowy',
    'stock_quantity'        => 'Stan magazynowy',
    'opening_count'         => 'Stan początkowy',
    'adjust'                => 'Koryguj stan magazynowy',
    'adjustment'            => 'Korekta',
    'set_to'                => 'Ustaw na',
    'change_by'             => 'Zmień o',
    'reason'                => 'Powód',
    'movements'             => 'Ruchy magazynowe',
    'no_movements'          => 'Brak ruchów magazynowych.',
    'via'                   => 'przez :item',
    'components'            => 'Komponenty',
    'no_components'         => 'Brak komponentów.',
    'add_component'         => 'Dodaj komponent',
    'quantity_per_unit'     => 'Ilość na jednostkę',
    'build'                 => 'Złóż',
    'assembly'              => 'Montaż',

    'form_description' => [
        'stock'             => 'Śledź, ile jednostek jest w magazynie. Faktury zdejmują jednostki ze stanu, a rachunki je dodają. Ujemny stan magazynowy jest dozwolony.',
        'components'        => 'Wymień artykuły, z których składa się ten artykuł. Jeśli ten artykuł nie śledzi stanu magazynowego, jego sprzedaż zdejmuje komponenty ze stanu. Jeśli śledzi, użyj „Złóż”, aby zamienić komponenty w gotowe jednostki.',
        'build'             => 'Zdejmuje komponenty ze stanu magazynowego i dodaje gotowe jednostki. Ujemna ilość ponownie rozkłada jednostki na komponenty.',
    ],

    'bulk' => [
        'track'             => 'Czy na pewno chcesz, aby wybrane :type <b>śledziły stan magazynowy</b>?',
        'untrack'           => 'Czy na pewno chcesz, aby wybrane :type <b>przestały śledzić stan magazynowy</b>?',
    ],

    'messages' => [
        'adjusted'          => 'Stan magazynowy artykułu :item wynosi teraz :quantity.',
        'built'             => 'Złożono :quantity szt. artykułu :item.',
        'tracked'           => 'Śledzenie stanu magazynowego włączono dla wybranych artykułów.',
        'untracked'         => 'Śledzenie stanu magazynowego wyłączono dla wybranych artykułów.',
    ],

    'errors' => [
        'component_is_self' => 'Artykuł nie może być komponentem samego siebie.',
        'component_is_kit'  => ':item ma już komponenty, więc nie może być użyty jako komponent.',
        'kit_as_component'  => ':item jest komponentem innego artykułu, więc nie może mieć własnych komponentów.',
        'not_tracked'       => ':item nie śledzi stanu magazynowego.',
        'no_components'     => ':item nie ma komponentów, z których można go złożyć.',
        'not_product'       => ':item jest usługą, więc nie może śledzić stanu magazynowego.',
    ],

];
