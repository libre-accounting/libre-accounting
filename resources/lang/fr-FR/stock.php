<?php

return [

    'stock'                 => 'Stock',
    'track_stock'           => 'Suivre le stock',
    'untrack_stock'         => 'Ne plus suivre le stock',
    'stock_quantity'        => 'Quantité en stock',
    'opening_count'         => 'Stock initial',
    'adjust'                => 'Ajuster le stock',
    'adjustment'            => 'Ajustement',
    'set_to'                => 'Définir à',
    'change_by'             => 'Modifier de',
    'reason'                => 'Motif',
    'movements'             => 'Mouvements de stock',
    'no_movements'          => 'Aucun mouvement de stock pour le moment.',
    'via'                   => 'via :item',
    'components'            => 'Composants',
    'no_components'         => 'Aucun composant pour le moment.',
    'add_component'         => 'Ajouter un composant',
    'quantity_per_unit'     => 'Quantité par unité',
    'build'                 => 'Assembler',
    'assembly'              => 'Assemblage',

    'form_description' => [
        'stock'             => 'Suivez le nombre d\'unités en stock. Les factures de vente sortent des unités du stock et les factures d\'achat en font entrer. Un stock négatif est autorisé.',
        'components'        => 'Listez les articles à partir desquels celui-ci est assemblé. Si cet article ne suit pas le stock, sa vente sort les composants du stock. S\'il le suit, utilisez Assembler pour transformer les composants en unités finies.',
        'build'             => 'Sort les composants du stock et ajoute les unités finies. Une quantité négative désassemble à nouveau les unités.',
    ],

    'bulk' => [
        'track'             => 'Êtes-vous sûr de vouloir <b>suivre le stock</b> des :type sélectionnés ?',
        'untrack'           => 'Êtes-vous sûr de vouloir <b>ne plus suivre le stock</b> des :type sélectionnés ?',
    ],

    'messages' => [
        'adjusted'          => 'Le stock de :item est désormais de :quantity.',
        'built'             => ':quantity unité(s) de :item assemblée(s).',
        'tracked'           => 'Le suivi du stock a été activé pour les articles sélectionnés.',
        'untracked'         => 'Le suivi du stock a été désactivé pour les articles sélectionnés.',
    ],

    'errors' => [
        'component_is_self' => 'Un article ne peut pas être un composant de lui-même.',
        'component_is_kit'  => ':item a déjà des composants et ne peut donc pas être utilisé comme composant.',
        'kit_as_component'  => ':item est utilisé comme composant d\'un autre article et ne peut donc pas avoir ses propres composants.',
        'not_tracked'       => ':item ne suit pas le stock.',
        'no_components'     => ':item n\'a aucun composant à assembler.',
        'not_product'       => ':item est un service et ne peut donc pas suivre le stock.',
    ],

];
