<?php

return [

    'stock'                 => 'Magazzino',
    'track_stock'           => 'Gestisci giacenza',
    'stock_quantity'        => 'Giacenza attuale',
    'opening_count'         => 'Giacenza iniziale',
    'adjust'                => 'Rettifica giacenza',
    'adjustment'            => 'Rettifica',
    'set_to'                => 'Imposta a',
    'change_by'             => 'Modifica di',
    'reason'                => 'Causale',
    'movements'             => 'Movimenti di magazzino',
    'no_movements'          => 'Ancora nessun movimento di magazzino.',
    'via'                   => 'tramite :item',
    'components'            => 'Componenti',
    'no_components'         => 'Ancora nessun componente.',
    'add_component'         => 'Aggiungi componente',
    'quantity_per_unit'     => 'Quantità per unità',
    'build'                 => 'Assembla',
    'assembly'              => 'Assemblaggio',

    'form_description' => [
        'stock'             => 'Tieni traccia di quante unità sono in giacenza. Le fatture scaricano le unità dal magazzino e le fatture di acquisto le caricano. La giacenza negativa è consentita.',
        'components'        => 'Elenca gli articoli da cui è composto questo articolo. Se questo articolo non gestisce la giacenza, vendendolo i componenti vengono scaricati dal magazzino. Se la gestisce, usa Assembla per trasformare i componenti in unità finite.',
        'build'             => 'Scarica i componenti dal magazzino e carica le unità finite. Una quantità negativa smonta di nuovo le unità.',
    ],

    'messages' => [
        'adjusted'          => 'La giacenza di :item è ora :quantity.',
        'built'             => 'Assemblate :quantity unità di :item.',
    ],

    'errors' => [
        'component_is_self' => 'Un articolo non può essere un componente di sé stesso.',
        'component_is_kit'  => ':item ha già dei componenti, quindi non può essere usato come componente.',
        'kit_as_component'  => ':item è usato come componente di un altro articolo, quindi non può avere componenti propri.',
        'not_tracked'       => ':item non gestisce la giacenza.',
        'no_components'     => ':item non ha componenti da assemblare.',
    ],

];
