<?php

return [

    'details'               => 'Detail|Details',

    'from_account'          => 'From Account',
    'to_account'            => 'To Account',
    'from_rate'             => 'From Rate',
    'to_rate'               => 'To Rate',
    'from_account_rate'     => 'From Account Rate',
    'to_account_rate'       => 'To Account Rate',
    'from_amount'           => 'From Amount',
    'to_amount'             => 'To Amount',
    'issued_at'             => 'Issue Date',
    'rate'                  => 'Rate',

    'link'                      => 'Link as transfer',
    'link_match_account'        => 'Match with account',
    'link_select_account_hint'  => 'Select an account to see matching transactions.',
    'link_all_dates'            => 'Show all dates',
    'link_no_candidates'        => 'No matching transactions found.',

    'errors' => [
        'link_not_linkable'     => 'This transaction cannot be linked as a transfer.',
        'link_invalid'          => 'These transactions cannot be linked.',
        'link_same_transaction' => 'A transaction cannot be linked to itself.',
        'link_already_transfer' => 'One of the transactions is already part of a transfer.',
        'link_reconciled'       => 'A reconciled transaction cannot be linked as a transfer.',
        'link_has_document'     => 'A transaction matched to an invoice or bill cannot be linked as a transfer.',
        'link_same_account'     => 'The two transactions must be on different accounts.',
        'link_same_direction'   => 'A transfer needs one incoming and one outgoing transaction.',
    ],

    'form_description' => [
        'general'           => 'Transfer money between accounts with different currencies and peg the currency to whichever rate you may like.',
        'other'             => 'Select the transfer method as the payment method to make your reports more detailed.',
    ],

    'messages' => [
        'delete'            => ':from to :to (:amount)',
    ],

    'slider' => [
        'create'            => ':user created this transfer on :date',
        'transactions'      => 'List of transactions related to this transfer',
        'transactions_desc' => ':number transaction for :account',
        'attachments'       => 'Download the files attached to this transfer',
    ],

];
