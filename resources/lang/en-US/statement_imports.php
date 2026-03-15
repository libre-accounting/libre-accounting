<?php

return [

    'form_description'  => 'Upload a CAMT.053 (ISO 20022 XML) bank statement to import its transactions. The lines are staged for review before anything is added to your books.',
    'account'           => 'Account',
    'file'              => 'Statement file',

    'statement'         => 'Statement',
    'period'            => 'Period',
    'booked_at'         => 'Booked',
    'value_date'        => 'Value date',
    'counterparty'      => 'Counterparty',
    'remittance'        => 'Remittance information',
    'reference'         => 'Reference',
    'lines'             => 'Lines',
    'imported'          => 'Imported',
    'total_lines'       => 'Total lines',
    'imported_lines'    => 'Imported lines',

    'statuses' => [
        'pending'   => 'Pending',
        'imported'  => 'Imported',
        'skipped'   => 'Skipped',
        'duplicate' => 'Duplicate',
        'reviewing' => 'Reviewing',
        'completed' => 'Completed',
    ],

    'import_selected'   => 'Import selected',
    'select_all'        => 'Select all',
    'set_category_all'  => 'Set category for all selected',

    'messages' => [
        'staged'        => ':count line(s) parsed and ready for review.',
        'committed'     => ':count transaction(s) created.',
        'truncated'     => 'The statement was large: :count line(s) beyond the limit were not imported.',
        'iban_mismatch' => 'The statement IBAN does not match the selected account. Please double-check the account.',
    ],

    'errors' => [
        'not_xml'           => 'The statement must be a CAMT.053 XML file.',
        'unreadable'        => 'The uploaded file could not be read.',
        'already_imported'  => 'This exact file has already been imported.',
        'staging_failed'    => 'The statement could not be staged for review. Please try again.',
        'none_selected'     => 'Select at least one line to import.',
        'category_required' => 'Choose a category for every selected line before importing.',
    ],

];
