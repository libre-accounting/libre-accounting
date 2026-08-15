<?php

return [

    'stock'                 => 'Lagerbestand',
    'track_stock'           => 'Lagerbestand führen',
    'stock_quantity'        => 'Aktueller Bestand',
    'opening_count'         => 'Anfangsbestand',
    'adjust'                => 'Bestand korrigieren',
    'adjustment'            => 'Korrektur',
    'set_to'                => 'Setzen auf',
    'change_by'             => 'Ändern um',
    'reason'                => 'Grund',
    'movements'             => 'Lagerbewegungen',
    'no_movements'          => 'Noch keine Lagerbewegungen.',
    'via'                   => 'über :item',
    'components'            => 'Komponenten',
    'no_components'         => 'Noch keine Komponenten.',
    'add_component'         => 'Komponente hinzufügen',
    'quantity_per_unit'     => 'Menge pro Einheit',
    'build'                 => 'Fertigen',
    'assembly'              => 'Fertigung',

    'form_description' => [
        'stock'             => 'Verfolgen Sie, wie viele Einheiten auf Lager sind. Ausgangsrechnungen buchen Einheiten aus dem Bestand aus, Eingangsrechnungen buchen sie ein. Ein negativer Bestand ist zulässig.',
        'components'        => 'Führen Sie die Artikel auf, aus denen dieser Artikel zusammengesetzt ist. Führt dieser Artikel keinen Lagerbestand, werden beim Verkauf die Komponenten aus dem Bestand ausgebucht. Führt er einen Lagerbestand, verwenden Sie „Fertigen“, um Komponenten in fertige Einheiten umzuwandeln.',
        'build'             => 'Bucht die Komponenten aus dem Bestand aus und die fertigen Einheiten ein. Eine negative Menge zerlegt die Einheiten wieder.',
    ],

    'messages' => [
        'adjusted'          => 'Der Bestand von :item beträgt jetzt :quantity.',
        'built'             => 'Es wurden :quantity Einheiten von :item gefertigt.',
    ],

    'errors' => [
        'component_is_self' => 'Ein Artikel kann nicht Komponente seiner selbst sein.',
        'component_is_kit'  => ':item hat bereits Komponenten und kann daher nicht als Komponente verwendet werden.',
        'kit_as_component'  => ':item wird als Komponente eines anderen Artikels verwendet und kann daher keine eigenen Komponenten haben.',
        'not_tracked'       => ':item führt keinen Lagerbestand.',
        'no_components'     => ':item hat keine Komponenten, aus denen gefertigt werden könnte.',
    ],

];
