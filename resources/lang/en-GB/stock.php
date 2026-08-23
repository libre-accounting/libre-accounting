<?php

return [

    'stock'                 => 'Stock',
    'track_stock'           => 'Track stock',
    'untrack_stock'         => 'Stop tracking stock',
    'stock_quantity'        => 'Stock on hand',
    'opening_count'         => 'Opening count',
    'adjust'                => 'Adjust stock',
    'adjustment'            => 'Adjustment',
    'set_to'                => 'Set to',
    'change_by'             => 'Change by',
    'reason'                => 'Reason',
    'movements'             => 'Stock movements',
    'no_movements'          => 'No stock movements yet.',
    'via'                   => 'via :item',
    'components'            => 'Components',
    'no_components'         => 'No components yet.',
    'add_component'         => 'Add component',
    'quantity_per_unit'     => 'Quantity per unit',
    'build'                 => 'Build',
    'assembly'              => 'Assembly',

    'form_description' => [
        'stock'             => 'Track how many units are in stock. Invoices take units out and bills bring them in. Negative stock is allowed.',
        'components'        => 'List the items this one is built from. If this item does not track stock, selling it takes the components out of stock. If it does, use Build to turn components into finished units.',
        'build'             => 'Takes the components out of stock and adds the finished units. A negative quantity takes units apart again.',
    ],

    'bulk' => [
        'track'             => 'Are you sure you want to <b>track stock</b> for the selected :type?',
        'untrack'           => 'Are you sure you want to <b>stop tracking stock</b> for the selected :type?',
    ],

    'messages' => [
        'adjusted'          => 'Stock of :item is now :quantity.',
        'built'             => 'Built :quantity of :item.',
        'tracked'           => 'Stock tracking switched on for the selected items.',
        'untracked'         => 'Stock tracking switched off for the selected items.',
    ],

    'errors' => [
        'component_is_self' => 'An item cannot be a component of itself.',
        'component_is_kit'  => ':item already has components, so it cannot be used as a component.',
        'kit_as_component'  => ':item is used as a component of another item, so it cannot have components of its own.',
        'not_tracked'       => ':item does not track stock.',
        'no_components'     => ':item has no components to build from.',
        'not_product'       => ':item is a service, so it cannot track stock.',
    ],

];
