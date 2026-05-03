<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Archive schema version
    |--------------------------------------------------------------------------
    |
    | The current on-disk format of a company archive. Bumped whenever the
    | serialized structure changes. An import refuses archives whose
    | schema_version is higher than this (created by a newer instance).
    |
    */

    'schema_version' => 1,

    /*
    |--------------------------------------------------------------------------
    | Insert / read batch size
    |--------------------------------------------------------------------------
    |
    | Rows are streamed in batches this size on both export and import to keep
    | memory flat regardless of company size.
    |
    */

    'batch_size' => env('COMPANY_BACKUPS_BATCH_SIZE', 500),

    /*
    |--------------------------------------------------------------------------
    | Include uploaded files
    |--------------------------------------------------------------------------
    |
    | Whether to bundle physical media files (attachments, logos) into the
    | archive. Disable for a metadata-only backup.
    |
    */

    'include_files' => env('COMPANY_BACKUPS_INCLUDE_FILES', true),

    /*
    |--------------------------------------------------------------------------
    | Staging disk for uploaded import archives
    |--------------------------------------------------------------------------
    |
    | Uploaded backup archives are staged here before the import job consumes
    | them. Kept separate from the 'temp' disk so the daily storage-temp:clear
    | sweep (17:00) cannot delete a queued-but-not-yet-run import upload.
    |
    */

    'staging_disk' => env('COMPANY_BACKUPS_STAGING_DISK', 'local'),
    'staging_path' => 'backups/incoming',

    /*
    |--------------------------------------------------------------------------
    | Retention
    |--------------------------------------------------------------------------
    |
    | Days a completed backup (row + produced download media) is kept before
    | company-backups:prune removes it. Only runs where the scheduler is wired.
    |
    */

    'retention_days' => env('COMPANY_BACKUPS_RETENTION_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Tables
    |--------------------------------------------------------------------------
    |
    | The full ordered set of per-company tables captured by a backup. Export
    | walks this list; import inserts in this order (parents before children).
    | Self-referential / id-list columns are patched in a second phase.
    |
    | This extends the canonical DeleteCompany relationship list with the five
    | tables it omits (media, mediables, item_taxes, bank_statement_imports,
    | bank_statement_lines) plus the user_dashboards pivot.
    |
    */

    'tables' => [
        'currencies',
        'categories',
        'taxes',
        'accounts',
        'contacts',
        'items',
        'item_taxes',
        'documents',
        'document_items',
        'document_item_taxes',
        'document_totals',
        'document_histories',
        'transactions',
        'transfers',
        'reconciliations',
        'recurring',
        'dashboards',
        'widgets',
        'reports',
        'email_templates',
        'modules',
        'module_histories',
        'bank_statement_imports',
        'bank_statement_lines',
        'media',
        'mediables',
        'settings',
    ],

];
